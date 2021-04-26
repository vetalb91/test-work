<?php
namespace Models;

use Models\Model;

class Organization extends Model
{
    /** 
     * @param array $org
     * @param array $data
     * @param bool $is_open
     * @param int $weekend
     */
    protected $data = [];
    public $org = [];
    public $is_open = null;
    public $weekend = 5;


    public function listOrganization(){
        
        $sql = 'SELECT id,name,day_of_week,open,close
                FROM organization JOIN schedule
                ON organization.id = schedule.organization_id;';

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

         while($row = $stmt->fetchAll(\PDO::FETCH_OBJ)) {
             $this->data = $row;
         }        
     
        return $this->schedule($this->data);
    }

    public function schedule(array $data)
    {
         foreach($data as $key => $item){

            if($this->day($item->day_of_week) != $this->weekend){
                
                
                $this->org[$item->id]['name'] = $item->name;
                $this->org[$item->id]['weekend'] =  $this->weekend();
                $this->org[$item->id]['is_open'] = $this->is_open;       

                     
            }else{

                $this->org[$item->id]['name'] = $item->name;
                $this->org[$item->id]['weekdays'] = $this->weekdays($item->open,$item->close);
                $this->org[$item->id]['schedule'] = substr($item->open,0,-3)."-".substr($item->close,0,-3);
                $this->org[$item->id]['is_open'] = $this->is_open;  
                   
            }
          
         } 
         
         return $this->org;
    }


     
     public function weekdays(string $open, string $close)
     {
        // количество рабочих часов
        $work_h = $this->workingHours($open,$close); 
       
        return  $work_h;
     }


     public function weekend()
     {
            return "Выходной день.";
     }

     public function workingHours(string $open, string $close)
     {
        
        // количество часов в рабочем дне 
        $work_time = $this->countWorkDay($open,$close);    

       
       if($work_time) {
         
          return $this->beforeClosing($close);

       }else{
         
           return $this->beforeOpening($open);
       }
    
     }
     
     
     public function countWorkDay($open,$close)
     {
       $cur_time = new DateTime();
       $cur_time->format('H:i:s');
       
       $time_to_begin = new DateTime($open);
       $time_to_end = new DateTime($close);

        if ($cur_time->getTimestamp() > $time_to_begin->getTimestamp() && $cur_time->getTimestamp() < $time_to_end->getTimestamp()){
             
             return  $this->is_open = true;  
        }else{
             return  $this->is_open = false;  
            }
     }



     public function beforeClosing(string $close)
     {
          $cur_time = new \DateTime();
          $time_to_close = new \DateTime($close);    
          $time = $cur_time->diff($time_to_close);

          
          return $time->format('%h часа(ов) и %i минут до закрытия');
     }

     public function beforeOpening(string $open)
     {
          $cur_time = new \DateTime();
          $time_to_open = new \DateTime('tomorrow'.$open);    

          $time = $cur_time->diff($time_to_open);

          return $time->format('%h часа(ов) и %i минут до открытия');
     }



     public function day(array $day){

       $day = new \DateTime();
        
        if(array_search($day->format('l'), json_decode($days))+1)
        {
           return true; 
            
        }else{
            
           return false; 
           
        }
     }



}  
 