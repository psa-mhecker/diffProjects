<?phpnamespace Citroen\Service\SimulFin\Model;

use Itkg\Service\Model as BaseModel;

/** * Classe OpenSessionResponse.
 */class OpenSessionResponse extends BaseModel
{    protected $responseSave;    /**     *     */    public function __toLog()
    {
        return ' Response : OK';

    }

}
