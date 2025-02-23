<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AsignacionSolicitud;

/**
 * AsignacionSolicitudSearch represents the model behind the search form about `backend\models\AsignacionSolicitud`.
 */
class AsignacionSolicitudSearch extends AsignacionSolicitud
{
    /**
     * @inheritdoc
     */

    public $globalSearch;
    public $role_id_constante = 40;

    public function rules()
    {
        return [
            [['asignacion_id', 'numero_inventario'], 'integer'],
            [['fecha_hora_inicio', 'fecha_hora_fin', 'equipo_reparado', 'observaciones','globalSearch','solicitud_id', 'estado_id','usuario_id'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'globalSearch' => "Buscar",
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }



        public function search($params,$user_id,$role_id)
    {
        $query = AsignacionSolicitud::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        $this->load($params);

        if (!$this->validate()) {           
            return $dataProvider;
        }

        
      $query->joinWith('user');
      $query->joinWith('solicitud');
      $query->joinWith('estado');
      //$query->where(['user_id'=> (Yii::$app->user->identity->id)]);
      if ($role_id!=$this->role_id_constante) {
           $query->where(['user.id'=>$user_id]);
        }

      $query->andFilterWhere([
            'asignacion_id' => $this->asignacion_id,
            'user_id' => $this->usuario_id,
        ]);

      $query->andFilterWhere(['like', 'asignacion_id', $this->globalSearch])
            ->andFilterWhere(['like', 'numero_inventario', $this->globalSearch])
            ->andFilterWhere(['like', 'equipo_reparado', $this->globalSearch])
            ->andFilterWhere(['like', 'user.nombre_completo', $this->globalSearch])
            ->andFilterWhere(['like', 'estado.nombre', $this->globalSearch])
            ->andFilterWhere(['like', 'solicitud.description', $this->globalSearch])
            ->andFilterWhere(['like', 'observaciones', $this->globalSearch])
            ->andFilterWhere(['like', 'fecha_hora_inicio', $this->globalSearch])
            ->andFilterWhere(['like', 'fecha_hora_fin', $this->globalSearch]); 

        return $dataProvider;        
    }
}
