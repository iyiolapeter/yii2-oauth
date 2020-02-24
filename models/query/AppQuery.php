<?php

namespace pso\yii2\oauth\models\query;

/**
 * This is the ActiveQuery class for [[\pso\yii2\oauth\models\App]].
 *
 * @see \pso\yii2\oauth\models\App
 */
class AppQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \pso\yii2\oauth\models\App[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \pso\yii2\oauth\models\App|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
