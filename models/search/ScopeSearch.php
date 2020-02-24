<?php

namespace pso\yii2\oauth\models\search;

use Yii;
use yii\rbac\Item;
use yii\data\ArrayDataProvider;
use dosamigos\arrayquery\ArrayQuery;
use yii2mod\rbac\models\search\AuthItemSearch;
use yii\helpers\StringHelper;

class ScopeSearch extends AuthItemSearch
{
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return \yii\data\ArrayDataProvider
     */
    public function search(array $params): ArrayDataProvider
    {
        $authManager = Yii::$app->getAuthManager();

        if ($this->type == Item::TYPE_ROLE) {
            $items = $authManager->getRoles();
        } else {
            $items = array_filter($authManager->getPermissions(), function ($item) {
                return strpos($item->name, '/') !== 0 && StringHelper::startsWith($item->name, '[Scope]');
            });
        }

        $query = new ArrayQuery($items);

        $this->load($params);

        if ($this->validate()) {
            $query->addCondition('name', $this->name ? "~{$this->name}" : null)
                ->addCondition('ruleName', $this->ruleName ? "~{$this->ruleName}" : null)
                ->addCondition('description', $this->description ? "~{$this->description}" : null);
        }

        return new ArrayDataProvider([
            'allModels' => $query->find(),
            'sort' => [
                'attributes' => ['name'],
            ],
            'pagination' => [
                'pageSize' => $this->pageSize,
            ],
        ]);
    }
}