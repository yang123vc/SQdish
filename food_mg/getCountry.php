<?php
if(true)
{
    $file = dirname(__FILE__).'/LocList.xml';
    $obj  = simplexml_load_file($file);
     
    $CountryArr = array();
    $StateArr   = array();
    $CityArr    = array();
    $RegionArr  = array();
     
    $cCode = 1; $cState = 1; $cCity = 1; $cRegion = 1;
     
    foreach ( $obj as $CountryRegion ) {
         
        $CountryArr[] = array('Name'=>(string)$CountryRegion['Name'],'Code'=>'c'.$cCode);
         
        foreach ( $CountryRegion as $State ) {
             
            if(!empty($State['Name']))
            {
                $StateArr['c'.$cCode][] = array('Name'=>(string)$State['Name'],'Code'=>'s'.$cState);
            }
             
            foreach ( $State as $City ) {
                 
                if(!empty($City['Name']))
                {
                    if(!empty($State['Code']))
                        $CityArr['s'.$cState][] = array('Name'=>(string)$City['Name'],'Code'=>'c'.$cCity);
                    else
                        $CityArr['c'.$cCode][] = array('Name'=>(string)$City['Name'],'Code'=>'c'.$cCity);
                }
                 
                foreach ( $City as $Region ) {
                     
                    if(!empty($Region['Name']))
                    {
                        if(!empty($City['Code']))
                            $RegionArr['c'.$cCity][] = array('Name'=>(string)$Region['Name'],'Code'=>'r'.$cRegion);
                    }
                    #县级代码
                    $cRegion++;
                }
                #城市代码
                $cCity++;   
            }
            #省份代码
            $cState++;
        }
        #国家代码
        $cCode++;
    }
    //echo '<pre>';print_r(array('country'=>$CountryArr,'state'=>$StateArr,'city'=>$CityArr,'region'=>$RegionArr));exit;
    echo(json_encode(array('country'=>$CountryArr,'state'=>$StateArr,'city'=>$CityArr,'region'=>$RegionArr)));exit;
}
?>