<?php

namespace pso\yii2\oauth\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use pso\yii2\oauth\models\OauthClient;

/**
 * OauthClientSearch represents the model behind the search form of `pso\yii2\oauth\models\OauthClient`.
 */
class OauthClientSearch extends OauthClient
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'logo', 'client_id', 'client_secret', 'grant_types', 'redirect_uri', 'created_at', 'updated_at'], 'safe'],
            [['auth_user_id', 'user_id', 'trusted', 'created_by', 'updated_by'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = OauthClient::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'auth_user_id' => $this->auth_user_id,
            'user_id' => $this->user_id,
            'trusted' => $this->trusted,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'client_id', $this->client_id])
            ->andFilterWhere(['like', 'client_secret', $this->client_secret])
            ->andFilterWhere(['like', 'grant_types', $this->grant_types])
            ->andFilterWhere(['like', 'redirect_uri', $this->redirect_uri]);

        return $dataProvider;
    }
}
