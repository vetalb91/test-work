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
    public $weekend = 6;


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
                $this->org[$item->id]['weekdays'] = $this->weekdays($item->open,$item->close);
                $this->org[$item->id]['schedule'] = substr($item->open,0,-3)."-".substr($item->close,0,-3);
                $this->org[$item->id]['is_open'] = $this->is_open;  
                     
            }else{

                $this->org[$item->id]['name'] = $item->name;
                $this->org[$item->id]['weekend'] =  $this->weekend();
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
          $this->is_open = true;  
          return $this->beforeClosing($close);

       }else{
           $this->is_open = false;  
           return $this->beforeOpening($open);
       }
    
     }

     public function countWorkDay(string $open, string $close)
     {
        // определение количество часов в рабочем дне 
        $time_to_open = new \DateTime($open);
        $time_to_close = new \DateTime($close);  
        $hour_work = $time_to_open->diff($time_to_close);
  
        $time_cur = new \DateTime();

        // прошедшее время с начала рабочего дня
        if($time_cur->format("H:i:s") >= "00:00:00"){
           $time_to_start = new \DateTime('yesterday'.$open);  
        }else{
           $time_to_start = new \DateTime($open);  
        }
    
        // высчитываем разницу
        $remaining_time = $time_cur->diff($time_to_start);

        return $remaining_time->h < $hour_work->h;

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
          $time_to_open = new \DateTime($open);    

          $time = $cur_time->diff($time_to_open);

          return $time->format('%h часа(ов) и %i минут до открытия');
     }



     public function day(int $day){

        $day = new \DateTime($day);

        return $day->format('N');

     }



}  
 