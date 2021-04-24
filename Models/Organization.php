<?php
namespace Models;

use Models\Model;

class Organization extends Model
{
    /** 
     * @param array $org
     * @param array $data
     * @param bool $is_open
     */
    protected $data = [];
    public $org = [];
    public $is_open = null;
    


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
             
             

            if($this->weekendDay($item->day_of_week)){
                
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
       // график работы
       $work_time = $this->workTime($open,$close);    

       
       if($work_time) {
         
          return $this->beforeClosing($close);

       }else{
         
           return $this->beforeOpening($open);
       }
      
     }


     public function weekend()
     {
            return "Выходной день.";
     }

   
     public function workTime($open,$close)
     {
       $cur_time = new \DateTime();
       $cur_time->format('H:i:s');
       
       $time_to_begin = new \DateTime($open);
       $time_to_end = new \DateTime($close);

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



     public function weekendDay($day){

        $day = new \DateTime($day);
        
        
        if($day->format('N') == 6 || $day->format('N') == 7)
        {
           return true; 
        }else{
           return false; 
        }
         

     }


}  
 