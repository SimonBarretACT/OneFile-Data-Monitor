<?php

class MyIterator_Filter_Archived extends FilterIterator {

public function accept() {
    $value = $this->current();
    if (array_key_exists('Group', $value)
     && 
     substr($value['Group'], 0, 8) === "Archived") {
        return false;
     }
        
    return true;
}

}

class MyIterator_Filter_LoggedIn extends FilterIterator {

public function accept() {
    $value = $this->current();
    if (array_key_exists('DateLastLoggedIn', $value)
     && 
     $value['DateLastLoggedIn']) {
        return true;
     }
        
    return false;
}

}

class MyIterator_Filter_LastWeek extends FilterIterator {

public function accept() {
    $value = $this->current();
    if (array_key_exists('DateLastLoggedIn', $value)
     && 
     $value['DateLastLoggedIn']) {
        
        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $value['DateLastLoggedIn']);
        $dateLastWeek = new DateTime('-7 days');

        return  (($value['Group'] == 'Learner') && ($dateTime >= $dateLastWeek));
     }
        
    return false;
}

}

class MyIterator_Filter_Archive extends FilterIterator {

    public function accept() {
        $value = $this->current();
        if (array_key_exists('DateLastLoggedIn', $value)
         && 
         $value['DateLastLoggedIn']) {
            
            $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $value['DateLastLoggedIn']);
            $dateArchive = new DateTime('-90 days');
    
            return  ($dateTime < $dateArchive);
         }
            
        return false;
    }
    
}