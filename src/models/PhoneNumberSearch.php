<?php

namespace maissoftware\sms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use maissoftware\sms\models\PhoneNumber;

/**
 * PhoneNumberSearch represents the model behind the search form about `maissoftware\sms\models\PhoneNumber`.
 */
class PhoneNumberSearch extends PhoneNumber
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity_id'], 'integer'],
            [['phone_number', 'extension', 'table_name', 'description'], 'safe'],
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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PhoneNumber::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'entity_id' => $this->entity_id,
        ]);

        $query->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'extension', $this->extension])
            ->andFilterWhere(['like', 'table_name', $this->table_name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
