<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class apiController extends Controller
{
    private function getAllFlights() {
      $api = Http::get('http://prova.123milhas.net/api/flights');
      $apiResult = $api->json();
      return $apiResult;
    }

    private function groupFlightsByFare() {
      $voos = apiController::getAllFlights();
      $groups = array();
      $group = array();
      $idGroup = 1;
      $identificador = 'Primeiro';
      foreach($voos as $voo){
        if($identificador !=  $voo['fare']){
            foreach($voos as $voo2){
                if($voo['fare'] == $voo2['fare']){
                    array_push($group, $voo2);
                } 
            }
            array_push($groups, array('FareId' => $idGroup, 'Fare' => $voo['fare'], 'Voos' => $group));
            $group = array();
            $idGroup++;
        }

        $identificador = $voo['fare'];
      }
      
      return $groups;
    }

    
    private function groupFlightsByPriceAndOutIn() {
      $groupsFare = apiController::groupFlightsByFare();
      $groups = array();
      $group = array();
      //$group2 = array();
      $idGroup = 1;
      $identificador = 'Primeiro';
      $identi = 'Primeiro';
      foreach($groupsFare as $grupo){
            while($grupo['FareId'] == $idGroup){
                foreach($grupo['Voos'] as $voo){
                    if($identificador !=  $voo['price']){
                        foreach($grupo['Voos'] as $voo2){
                            if($voo['price'] == $voo2['price'] && $voo['outbound'] == $voo2['outbound']){
                                array_push($group, $voo2['id']);
                            } else if($voo['price'] == $voo2['price'] && $voo['outbound'] != $voo2['outbound']){
                                if($identi !=  $voo2['price']){
                                    foreach($grupo['Voos'] as $voo3){
                                        if($voo2['price'] == $voo3['price'] && $voo2['outbound'] == $voo3['outbound']){
                                            array_push($group2, $voo3['id']);
                                        }
                                    }
                                }
                               $identi = $voo2['price'];
                            }
                        }
                        if(!empty($group)){
                           array_push($groups, array('FareId' => $grupo['FareId'],
                                                    'Fare' => $grupo['Fare'],
                                                    'price' => $voo['price'],
                                                    'outbound' => $voo['outbound'],
                                                    'Voos' => $group
                                                    )
                           );
                        }
                        if (!empty($group2)){
                            array_push($groups, array('FareId' => $grupo['FareId'],
                                                    'Fare' => $grupo['Fare'],
                                                    'price' => $voo['price'],
                                                    'outbound' => $voo['inbound'],
                                                    'Voos' => $group2
                                                    )
                            );
                        }
                        $group = array();
                        $group2 = array();
                    }
                    $identificador = $voo['price'];
                }
                $idGroup++;
            }   
      }
      return $groups;
    }

    private function groupFlights() {
      $groupsPrice = apiController::groupFlightsByPriceAndOutIn();
      $groups = array();
      $group = array();
      $idGroup = 1;
      
      foreach($groupsPrice as $grupo){
            if($grupo['outbound']){
                $grupoOut = $grupo['Voos'];
            } else{
                continue;
            }
            foreach($groupsPrice as $grupo2){
                 if(!$grupo2['outbound'] && $grupo2['FareId'] == $grupo['FareId']){
                     $grupoIn = $grupo2['Voos'];
                     $totalGrupo = $grupo['price'] + $grupo2['price'];
                     array_push($groups, array('uniqueId' =>  $idGroup,
                                    'totalPrice' => $totalGrupo,
                                    'outbound' => $grupoOut,
                                    'inbound' => $grupoIn
                                    )
                     );
                     $idGroup++;
                 }
            }
      }
      return $groups;
    }

    private function orderByPrice() {
      $groups = apiController::groupFlights();
      $result = array();
      $result = collect($groups)->sortBy('totalPrice');
      
      return $result;
    }

    

    private function cheapestPrice() {
      $groups = apiController::groupFlights();
      $cheapestGroup = array();
      $menorPreco = 1000000000;
      
      foreach($groups as $grupo){
            if($grupo['totalPrice']<$menorPreco){
                $menorPreco = $grupo['totalPrice'];
                $idMenorPreco = $grupo['uniqueId'];
            }
      }
      $cheapestGroup = array('uniqueId' => $idMenorPreco, 'price' => $menorPreco);
      return $cheapestGroup;
    }

    public function result() {
      $groups = apiController::orderByPrice();
      $voos = apiController::getAllFlights();
      $menorPreco = apiController::cheapestPrice();
      
      $result = array();

      array_push($result, array('flights' =>  $voos,
                                'groups' => $groups,
                                'totalGroups' => count($groups),
                                'totalFlights' => count($voos),
                                'cheapestPrice' => $menorPreco['price'],
                                'cheapestGroup' => $menorPreco['uniqueId']
                                    )
                     );
      
      return response()->json($result, 200);
    }
      
}
