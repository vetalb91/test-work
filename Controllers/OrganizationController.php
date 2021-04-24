<?php

namespace Controllers;

use System\View;
use Models\Organization;


/**
 * Главный контроллер
 */
class OrganizationController
{
    /**
    * @param array $open
    * @param array $close
    * @param array $weekends
    */
    public $open = [];
    public $close = [];
    public $weekends = [];

    public function actionList()
    {
        // Создаем модель
        $model = new Organization();

        // Получаем данные используя модель
        $org = $model->listOrganization();

        // сортируем 
        foreach($org as $item){

            if($item['is_open']){

                $this->open[] = $item;

            }elseif(!$item['is_open'] && $item['is_open'] !== null){

                $this->close[] = $item; 

            }else{
                $this->weekends[] = $item; 
            }
        }
        
        // Передаем данные представлению для их отображения
        View::render('list', ['open' => $this->open  ,'close' => $this->close , 'weekends' => $this->weekends  ]);
    }
}