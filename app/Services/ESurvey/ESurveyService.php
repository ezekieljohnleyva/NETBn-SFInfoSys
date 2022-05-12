<?php
namespace App\Services\ESurvey;

use App\Models\MolecularCreds;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use AidynMakhataev\LaravelSurveyJs\app\Models\Survey;
use AidynMakhataev\LaravelSurveyJs\app\Models\SurveyResult;



class ESurveyService
{  

   public static function getSurveyRating($id, $option = []){
       
       
        
        $query = SurveyResult::findOrFail($id);
        
        $elements = self::surveyElements(Survey::find($query->survey_id));
        $surveyRating = $query->json;


        if(in_array('sortQuestions', $option))
            $surveyRating = self::sortSurveyIndividual($surveyRating,$elements, $id);

        if((in_array('sortSurveyMatrix', $option)))
            $surveyRating = self::sortSurveyMatrix($surveyRating, $elements, $option, $id);
         
        return $surveyRating;
   }

   public static function getUnitResult($surveyResult, $id, $elements = [], $excludedQuestions = [], $option = []){

    
    $surveyRating = [];
        
 

        foreach($surveyResult as $key => $item){

            $item = self::excludeResults($item, $excludedQuestions);
        

            if(in_array('sortQuestion', $option))
                $item = self::sortSurvey($item, $elements, $id);


            if(in_array('sortSurveyMatrix', $option))
                 $item = self::sortSurveyMatrix($item, $elements, $option);
        



           
            foreach($item as $question => $result){
                if(!array_key_exists($question,$surveyRating))
                    $surveyRating[$question] = array();
                if(is_array($result)){
                    $surveyRating[$question]['perfectPoints'] = 5;
                    foreach($result as $row => $value){
                        if(array_key_exists($row,$surveyRating[$question])){              
                            $surveyRating[$question][$row]['points'] += $value;
                            $surveyRating[$question][$row]['respondents'] ++;
                        }
                        else{
                            $surveyRating[$question][$row] = [
                                'points' => $value,
                                'respondents' => 1
                            ];
                        }
                    };
                }else{
                    array_push($surveyRating[$question],$result);
                }
            } 
        }
        // dd($surveyRating);

    return $surveyRating;
   }

   public static function excludeResults($item, $excludedQuestions = []){

        foreach($excludedQuestions as $ex){
                
            if (array_key_exists($ex, $item)) {
                unset($item[$ex]);
            }
            
        }

        return $item;
    }

    public static function surveyElements($survey){
        $elements = [];
       
        $survey = $survey->json;

        foreach($survey['pages'] as $key => $pages){
            if(array_key_exists('elements', $pages))
                $elements = array_merge($elements,$pages['elements']);
        }
    
        return $elements;
    }


    public static function sortSurvey($items, $elements, $id){

     
       $survey_id = SurveyResult::where("survey_id", $id)->pluck("survey_id")->first();

        $survey = Survey::where("id", $survey_id)->first();
      
        
   
      $count = count($survey->json["pages"]);
      $countainer = array();
      for($x=0; $x<$count; $x++)
      {          
          $countainer[] = $survey->json["pages"][$x];
      }

      $count_elements = count($countainer);
      $comtainer_elements = array();

      for($x=0; $x<$count_elements; $x++)
      {          
          $comtainer_elements[] = $countainer[$x]["elements"];
      }

      $count_items = count($comtainer_elements);
      $comtainer_items = array();

      for($x=0; $x<$count_items; $x++)
      {          
       
          for($y=0; $y<count($comtainer_elements[$x]); $y++)
          {    
              $comtainer_items[] = $comtainer_elements[$x][$y]["name"]; 
          }    
      }


      $survey_result = SurveyResult::where("id", $id)->first();

    

      $surveyRating = array();
      foreach($comtainer_items as $id => $value)
      {

    
              if (array_key_exists($value, $items))
              {

                  $surveyRating[$value] = $items["$value"];
              }            
      }

      return $surveyRating;
  }

    public static function sortSurveyIndividual($items, $elements, $id){

        $survey_id = SurveyResult::where("id", $id)->pluck("survey_id");
       
        $survey = Survey::where("id", $survey_id)->first();
      
        
   
      $count = count($survey->json["pages"]);
      $countainer = array();
      for($x=0; $x<$count; $x++)
      {          
          $countainer[] = $survey->json["pages"][$x];
      }

      $count_elements = count($countainer);
      $comtainer_elements = array();

      for($x=0; $x<$count_elements; $x++)
      {          
          $comtainer_elements[] = $countainer[$x]["elements"];
      }

      $count_items = count($comtainer_elements);
      $comtainer_items = array();

      for($x=0; $x<$count_items; $x++)
      {          
       
          for($y=0; $y<count($comtainer_elements[$x]); $y++)
          {    
              $comtainer_items[] = $comtainer_elements[$x][$y]["name"]; 
          }    
      }

    

      $survey_result = SurveyResult::where("id", $id)->first();


      
      $surveyRating = array();
      foreach($comtainer_items as $id => $value)
      {

    
        if (array_key_exists($value, $items))
        {

            $surveyRating[$value] = $items["$value"];
        }               

          

  

      }

      
      

      return $surveyRating;
  }

    public static function sortSurveyMatrix($surveyRating, $elements, $option){
      
        foreach($surveyRating as $key => $item){
            
            if(is_array($item)){
             $order = [];
                     foreach($elements as $eleKey => $eleValue){
                        
                        if(array_key_exists('rows', $eleValue)){
                             if($eleValue['name']==$key){
                                 $order = $eleValue['rows'];
                               
                                 break;
                             }
                        }
                     }

                   
           
            $properOrderedArray = array_replace(array_flip($order), $item);
         
                
                $diff = array_diff_key($properOrderedArray,$item);
                // print_r($diff);

                $toRemove = array_keys($diff);
                foreach($toRemove as $forRemove){
                    unset($properOrderedArray[$forRemove]);
                }
            
            //remove element not exist in question
            if(in_array('filterMatrixBasedQuestion', $option))
                $properOrderedArray = self::filterElementByQuestion($properOrderedArray, $order);

            $surveyRating[$key] = $properOrderedArray;
           

             
         
             };
             
           
         }
         
    
         return $surveyRating;
    }

    public static function filterElementByQuestion($properOrderedArray, $order){
          //removing existing $properOrderedArray elements not exist in $order: by questions
            
          $diff2 = array_diff_key($properOrderedArray,array_flip($order));
            
          $toRemove2 = array_keys($diff2);
          foreach($toRemove2 as $forRemove2){
              unset($properOrderedArray[$forRemove2]);
          }
       
          return $properOrderedArray;
    }



}