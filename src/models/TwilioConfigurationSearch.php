<?php

namespace maissoftware\sms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use maissoftware\sms\models\TwilioConfiguration;

/**
 * TwilioConfigurationSearch represents the model behind the search form about `maissoftware\sms\models\TwilioConfiguration`.
 */
class TwilioConfigurationSearch extends TwilioConfiguration
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['sid', 'token', 'notify_service_sid', 'twilio_number'], 'safe'],
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
        $query = TwilioConfiguration::find();

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
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'sid', $this->sid])
            ->andFilterWhere(['like', 'token', $this->token])
            ->andFilterWhere(['like', 'notify_service_sid', $this->notify_service_sid])
            ->andFilterWhere(['like', 'twilio_number', $this->twilio_number]);

        return $dataProvider;
    }
}
