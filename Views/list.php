<?php
/**
 * @var array $data - массив 
 */
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

<h2 style="margin-top:20px" align="center" >Список организаций</h2>
<hr>


<table class="table">
  <thead>
    <tr>
      
      <th style="background-color:#f6f6f6" scope="col">Открытые организации</th>
    </tr>
  </thead>
 

        <?php
        if($open):
            foreach ($open as $item): ?>
            <tr>
                
                <td> <?= $item['name']; ?> ( рабочее время <?= $item['schedule']; ?> ) - <span><?= $item['weekdays']; ?></span> </td>   
            
            </tr>     
        <?php endforeach; ?>
          <?php else:?>
          <td><span>Подходящих организаций нет </span></td>  
        <?php endif;?>    
    
</table> 
<table class="table ">
  <thead>
    <tr>
      
      <th style="background-color:#f6f6f6" scope="col">Закрытые организации</th>
    </tr>
  </thead>   
                   
         <?php
         if($close):
         foreach ($close as $item): ?>
         <tr>
            <?php if(!$item['is_open'] && $item['is_open'] !== null ): ?>
              <td> <?= $item['name']; ?> ( рабочее время <?= $item['schedule']; ?> ) - <span><?= $item['weekdays']; ?></span> </td> 
            <?php endif; ?>
         </tr>       
        <?php endforeach; ?>
        <?php else:?>
          <td><span>Подходящих организаций нет </span></td>  
        <?php endif;?>       
    
</table>
<table class="table ">
  <thead>
    <tr>
      
      <th style="background-color:#f6f6f6" scope="col">Выходные организации</th>
    </tr>
  </thead>       
     
         <?php
         if($weekends):
           foreach ($weekends as $item): ?>
         <tr>
            <?php if($item['is_open'] === null): ?>
              <td> <?= $item['name']; ?> - <span><?= $item['weekend']; ?></span> </td>   
            <?php endif; ?>
         </tr>       
        <?php endforeach; ?>
        <?php else:?>
         <td> <span>Подходящих организаций нет </span></td>  
        <?php endif;?>            
    
</table>    
  
  





