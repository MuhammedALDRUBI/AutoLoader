<?php


class AutoLoader{

    static private $folderPath;

    static public function LoadClassesFromFolder($folderPath){
        try{
            self::$folderPath = realpath($folderPath);
           
            if(self::isFolderFound()){
                 //if it is a file .... will be included direct
                if(self::ifFileHasBeenIncluded()){return true;}
                //else ... folder 'files will be inclueded by loadingFiles Method
                return self::loadingFiles(); 
            }else{
                throw new Exception("Folder is not found !");
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
    }


     /////////////////////////////////////////////////////////////////////////////////////////
    //this method will be used to loading all files that are in a folder
    /////////////////////////////////////////////////////////////////////////////////////////
    static private function loadingFiles(){
        
        $folder_files_array = scandir(self::$folderPath); 
    
        foreach($folder_files_array as $key => $val){
            $file_path = self::$folderPath . DIRECTORY_SEPARATOR . $val;
            if($val != "." && $val != ".."){
                if(is_file($file_path) && self::checkExtenssionStatus($file_path)){
                    require_once $file_path; 
                }elseif(is_dir($file_path)){   
                    self::$folderPath = $file_path;
                    self::loadingFiles();
                }   
            } 
        }
        return true;
    }

    
    /////////////////////////////////////////////////////////////////////////////////////////
    //this method will be used to check if file exists
    /////////////////////////////////////////////////////////////////////////////////////////
    static private function isFolderFound(){
        if(file_exists(self::$folderPath)){   
                return true; 
        }else{
            return false;
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////////
    //this method will be used to check extenssion for each file
    /////////////////////////////////////////////////////////////////////////////////////////
    static private function checkExtenssionStatus($file_path){
        $file_name_array = explode("." , $file_path);
        $file_extenssion = end($file_name_array);
        $file_extenssion = strtolower($file_extenssion);

        if($file_extenssion == "php"){
            return true;
        }
        return false;
    }

    /////////////////////////////////////////////////////////////////////////////////////////
    //this method will be used to require single file 
    /////////////////////////////////////////////////////////////////////////////////////////
    static private function ifFileHasBeenIncluded(){
        if(is_file(self::$folderPath)){
            if(self::checkExtenssionStatus(self::$folderPath)){
                require_once self::$folderPath; return true;
            } 
        }else{
            return false;
        }
    }
} 