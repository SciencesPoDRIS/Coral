<?php
/**
 * @author: Cesar Hernandez
 * getLanguage: This method return the language code
 * getNameLang: This method return the name of the language
 */
class LangCodes{
    public function getLanguage($code){
        $all_lang=array(
            'fr'=>'fr_FR',
            'en'=>'en_US'
        );
        return $all_lang[$code];
    }
    public function getNameLang($code_lang){
        $name_lang=array(
            'fr'=>_('French'),
            'en'=>_('English'),
            'es'=>_('Spanish'),
            'pt'=>_('Portugese'),
            'de'=>_('German'),
            'mul'=>_('Multilingual'),
        );
        return $name_lang[$code_lang];
    }
}
?>