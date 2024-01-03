<?php

    // create options array for form.select dropdown from the Model object

    function optionsHelper( $data, $id='id', $valueKey='name' ) {
        $items = [];
        $valueKeys = explode('.',$valueKey);
        
        foreach ( $data as $item ) {
            $val ="";
            foreach($valueKeys as $vkey) {
                $val .= $item->{$vkey} . ' ';
            }

            $items[$item->{$id}] = trim($val);
        }
        return $items;
    }

    // User role checker 
    function role( $role ) {
        if(auth()->user()->role == $role) {
            return true;
        } 
        return false;
    }

    // check if logged in user is admin
    function admin() {
        if(auth()->user()->role == 'admin') {
            return true;
        } 
        return false;
    }



?>