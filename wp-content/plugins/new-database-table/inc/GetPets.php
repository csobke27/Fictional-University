<?php
class GetPets{

    function __construct(){
        global $wpdb;
        $tableName = $wpdb->prefix . 'pets';

        $this->args = $this->getArgs();
        $this->placeholders = $this->createPlaceholders();

        $query = "SELECT * FROM $tableName ";
        $countQuery = "SELECT COUNT(*) FROM $tableName ";
        $query .= $this->createWhereClause();
        $countQuery .= $this->createWhereClause();
        $query .= " LIMIT 100";
        $this->pets = $wpdb->get_results($wpdb->prepare($query, $this->placeholders));
        $this->resultCount = $wpdb->get_var($wpdb->prepare($countQuery, $this->placeholders));
    }

    function getArgs(){
        $temp = array();
        if(isset($_GET['species'])){
            $temp['species'] = sanitize_text_field($_GET['species']);
        }
        if(isset($_GET['minweight'])){
            $temp['minweight'] = sanitize_text_field($_GET['minweight']);
        }
        if(isset($_GET['maxweight'])){
            $temp['maxweight'] = sanitize_text_field($_GET['maxweight']);
        }
        if(isset($_GET['minyear'])){
            $temp['minyear'] = sanitize_text_field($_GET['minyear']);
        }
        if(isset($_GET['maxyear'])){
            $temp['maxyear'] = sanitize_text_field($_GET['maxyear']);
        }
        if(isset($_GET['favhobby'])){
            $temp['favhobby'] = sanitize_text_field($_GET['favhobby']);
        }
        if(isset($_GET['favcolor'])){
            $temp['favcolor'] = sanitize_text_field($_GET['favcolor']);
        }
        if(isset($_GET['favfood'])){
            $temp['favfood'] = sanitize_text_field($_GET['favfood']);
        }
        return array_filter($temp, function($x) {
            return $x;
        });
    }

    function createPlaceholders(){
        return array_map(function($x){
            return $x;
        }, $this->args);
    }

    function createWhereClause(){
        $whereClause = "";
        
        if(count($this->args) > 0){
            $whereClause .= "WHERE ";
            $conditions = array();
            foreach($this->args as $key => $value){
                $conditions[] = $this->getCompareType($key);
            }
            $whereClause .= implode(" AND ", $conditions);
        }
        return $whereClause;
    }

    function getCompareType($key){
        switch($key){
            case 'minweight':
                return 'petweight >= %d';
            case 'maxweight':
                return 'petweight <= %d';
            case 'minyear':
                return 'birthyear >= %d';
            case 'maxyear':
                return 'birthyear <= %d';
            default:
                return $key . ' = %s';
        }
    }
}
?>