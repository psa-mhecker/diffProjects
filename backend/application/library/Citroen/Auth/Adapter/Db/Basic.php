<?php

class Citroen_Auth_Adapter_Db_Basic extends Pelican_Auth_Adapter_Db_Basic
{
    protected $_isLdap;
    protected $_ldapField;
    public function setIsLdap($bIsLdap)
    {
        $this->_isLdap = $bIsLdap;
    }
    public function setLdapField($sLdapField)
    {
        $this->_ldapField = $sLdapField;
    }

    protected function getUserInfos()
    {
        $aBind[":LOGIN_VALUE"] = $this->oConnection->strToBind($this->_identity);
        $aBind[":PASS_VALUE"] = $this->oConnection->strToBind($this->_credential);

        $aBind[":TABLE_NAME"] = $this->_tableName;
        $aBind[":LOGIN"] = $this->_identityField;
        $aBind[":PASS"] = $this->_credentialField;

        if (!isset($this->_credentialTreatment)) {
            $aBind[":PASS_VALUE_T"] = $aBind[":PASS_VALUE"];
        } else {
            if ($this->_credentialTreatment == 'MD5(?)') {
                $aBind[":PASS_VALUE_T"] = $this->oConnection->strToBind(md5($this->_credential));
            }
            /* if (strtolower($this->oConnection->databaseTitle) == "oracle" || strtolower($this->oConnection->databaseTitle) == "ingres") {
              $val = "\$pwd = ".preg_replace('/\?/', "'".$aBind[":PASS_VALUE"]."'", strtolower($this->_credentialTreatment)).";";
              eval($val);
              $aBind[":PASS_VALUE_T"] = "'".$pwd."'";
              } else {
              $aBind[":PASS_VALUE_T"] = preg_replace('/\?/', $aBind[":PASS_VALUE"], $this->_credentialTreatment );
              } */
        }

       // if($this->_isLdap){
            $aBind[':IS_LDAP'] = (int) $this->_isLdap;
        $sWhereLdap = sprintf(' AND %s = :IS_LDAP', $this->_ldapField);
        //}

        // Récupération des informations de l'utilisateur prétendu
        $query = "select distinct
				".$aBind[":LOGIN"]." as \"id\",
				".$aBind[":PASS"]." as \"pwd\",
				T.*
				FROM
				".$aBind[":TABLE_NAME"]." T
				WHERE
				".$aBind[":LOGIN"]."=:LOGIN_VALUE
				AND ".$aBind[":PASS"]."=:PASS_VALUE_T
				";
        if ($sWhereLdap != '') {
            $query .= $sWhereLdap;
        }
        $return = $this->oConnection->queryRow($query, $aBind);

        return $return;
    }
}
