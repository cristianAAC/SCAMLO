<?php

namespace backend\models;

use Yii;
use common\models\User;
use backend\models\Estado;
use backend\models\Solicitud;
use yii\helpers\ArrayHelper;




/**
 * This is the model class for table "asignacion_solicitud".
 *
 * @property integer $asignacion_id
 * @property integer $solicitud_id
 * @property integer $estado_id
 * @property integer $usuario_id
 * @property string $fecha_hora_inicio
 * @property string $fecha_hora_fin
 * @property string $equipo_reparado
 * @property integer $numero_inventario
 * @property string $observaciones
 *
 * @property Estado $estado
 * @property Solicitud $solicitud
 * @property User $usuario
 */
class AsignacionSolicitud extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    
    public static function tableName()
    {
        return 'asignacion_solicitud';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'usuario_id', 'fecha_hora_inicio','estado_id'], 'required'],
            [['numero_inventario'], 'integer'],
            [['fecha_hora_inicio', 'fecha_hora_fin'], 'safe'],
            [['equipo_reparado'], 'string', 'max' => 80],
           // ['usuario_id', 'integer','max' => 12345600,'message'=> 'El trabajador seleccionado ya se le asigno esta tarea'],
            [['observaciones'], 'string', 'max' => 255],
            [['estado_id'], 'exist', 'skipOnError' => true, 'targetClass' => Estado::className(), 'targetAttribute' => ['estado_id' => 'id']],
            [['solicitud_id'], 'exist', 'skipOnError' => true, 'targetClass' => Solicitud::className(), 'targetAttribute' => ['solicitud_id' => 'id']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['usuario_id' => 'id']],
            ['usuario_id', 'default', 'value' => Yii::$app->user->identity->id],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'asignacion_id' => 'Asignacion ID',
            'solicitud_id' => 'Solicitud',
            'estado_id' => 'Estado',
            'usuario_id' => 'Trabajador',
            'fecha_hora_inicio' => 'Fecha Hora Inicio',
            'fecha_hora_fin' => 'Fecha Hora Fin',
            'equipo_reparado' => 'Equipo Reparado',
            'numero_inventario' => 'Numero Inventario',
            'observaciones' => 'Observaciones',
            'nombreUser' => 'Trabajador Encargado',
            'nombreSolicitud' =>'Solicitud',
            //'descriptionSolicitud' =>'Solicitud',
            'nombreEstado' =>'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstado()
    {
        return $this->hasOne(Estado::className(), ['id' => 'estado_id']);
    }

    public function getNombreEstado()
    {
        return $this->estado ? $this->estado->nombre : '- sin estado -';
    }

    /**
    * get list of Estados for dropdown
    */
    public static function getEstadoList()
    {
        $droptions = Estado::find()->asArray()->all();
        return Arrayhelper::map($droptions, 'id', 'nombre');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitud()
    {
        return $this->hasOne(Solicitud::className(), ['id' => 'solicitud_id']);
    }

    public function getNombreSolicitud()
    {
        return $this->solicitud ? $this->solicitud->description : '- sin estado -';
    }

    public static function getSolicitudList()
    {
        $droptions = Solicitud::find()->where(['<>', 'estado_id', 1])->asArray()->all();
        return Arrayhelper::map($droptions, 'id', 'description');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'usuario_id']);
    }

    public function getNombreUser()
    {
        return $this->user ? $this->user->nombre_completo : '- sin nombre -';
    }

    public static function getUserList()
    {
        $role_id_constante = 20;
        $droptions = User::find()->orwhere(['role_id'=>$role_id_constante])->asArray()->all();
        return Arrayhelper::map($droptions, 'id', 'nombre_completo');
    }
    /*public function getDescriptionSolicitud()
    {
        return $this->solicitud ? $this->solicitud->description : '- sin nombre -';
    }*/
    public function getId()
    {
        return $this->getPrimaryKey();
    }
}
