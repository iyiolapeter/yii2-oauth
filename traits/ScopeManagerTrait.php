<?php

namespace pso\yii2\oauth\traits;

use yii\rbac\Assignment;

trait ScopeManagerTrait
{
    private $scopeMode = false;
    private $oauthScopes = [];
    private $oauthAssignments = [];

    public function setScopes(array $scopes, $userId){
        foreach($scopes as $scope){
            $scope = "[Scope]$scope";
            $this->oauthScopes[$scope] = $this->createScopeAssignment($scope, $userId);
        }
        return $this;
    }

    public function createScopeAssignment($roleName, $userId, $time = NULL){
        return new Assignment([
            'roleName' => $roleName,
            'userId' => $userId,
            'createdAt' => $time??time()
        ]);
    }

    public function checkScopeAccess($userId, $scope, $params = []){
        $this->scopeMode = true;
        try {
            $access = $this->checkAccess($userId, $scope, $params);
            $this->scopeMode = false;
            return $access;
        } catch (\Throwable $th) {
            $this->scopeMode = false;
            throw $th;
        }
        
    }

    public function getAssignments($userId){
        if($this->scopeMode){
            return $this->oauthScopes;
        }
        return parent::getAssignments($userId);
    }
}