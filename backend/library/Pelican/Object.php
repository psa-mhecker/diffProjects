<?php
/**
 * Classe Object : standardise la manipulation des propriétés d'un objet.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */

/**
 * Classe Object : standardise la manipulation des propriétés d'un objet.
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Object
{
    /**
     * Tableau des erreurs.
     *
     * @access private
     *
     * @var array
     */
    private $_errors = array();

    /**
     * Renvoie une propriété de l'objet ou la valeur par défaut si la propriété
     * n'est
     * pas définie.
     *
     * @access public
     *
     * @param string $property Le nom de la propriété
     * @param string $default  (option) Valeur par défaut
     *
     * @return mixed
     */
    public function get($property, $default = null)
    {
        if (isset($this->$property)) {
            return $this->$property;
        }

        return $default;
    }

    /**
     * Retourne un tableau associatif des propriétés de l'objet.
     *
     * @access public
     *
     * @param bool $public (option) Si vrai, ne renvoie que les propriétés publiques
     *
     * @return array
     */
    public function getProperties($public = true)
    {
        $vars = get_object_vars($this);
        if ($public) {
            foreach ($vars as $key => $value) {

                /* cas des propriétés privées */
                if ('_' == substr($key, 0, 1)) {
                    unset($vars[$key]);
                }
            }
        }

        return $vars;
    }

    /**
     * Obtention du message d'erreur le plus récent.
     *
     * @access public
     *
     * @param int  $i        (option) Options sur indice d'erreur
     * @param bool $toString (option) Indique si les objets d'erreur doivent retourner
     *                       leur message d'erreur
     *
     * @return string
     */
    public function getError($i = null, $toString = true)
    {
        // Rercherche de l'erreur
        if ($i === null) {
            // Default, retourne le dernier message
            $error = end($this->_errors);
        } elseif (!array_key_exists($i, $this->_errors)) {
            // si $i a été précisé mais n'existe pas, retourne false
            return false;
        } else {
            $error = $this->_errors[$i];
        }
        // Vérifie si seule la chaîne est demandée
        if (Pelican_Error::isError($error) && $toString) {
            return $error->toString();
        }

        return $error;
    }

    /**
     * Retourne toutes les erreurs s'il en existe.
     *
     * @access public
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * Modifie une propriété de l'objet, la crée si elle n'existe pas déjà.
     *
     * @access public
     *
     * @param string $property Le nom de la propriété
     * @param mixed  $value    (option) La valeur de la propriété à définir
     *
     * @return mixed
     */
    public function set($property, $value = null)
    {
        $previous = isset($this->$property) ? $this->$property : null;
        $this->$property = $value;

        return $previous;
    }

    /**
     * Définissez les propriétés de l'objet repose sur un tableau nommé / hash.
     *
     * @access protected
     *
     * @param mixed $properties Tableau associatif ou un autre objet
     *
     * @return bool
     */
    protected function setProperties($properties)
    {
        $properties = (array) $properties;
        if (is_array($properties)) {
            foreach ($properties as $key => $value) {
                $this->$key = $value;
            }

            return true;
        }

        return false;
    }

    /**
     * Ajouter un message d'erreur.
     *
     * @access public
     *
     * @param string $error Message d'erreur
     */
    public function setError($error)
    {
        array_push($this->_errors, $error);
    }

    /**
     * Conversion de l'objet en chaîne de caractère.
     *
     * @access public
     *
     * @return string
     */
    public function toString()
    {
        return get_class($this);
    }
}
