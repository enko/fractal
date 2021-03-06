<?php

/*
 * This file is part of the League\Fractal package.
 *
 * (c) Phil Sturgeon <email@philsturgeon.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\Fractal;

class Scope
{
    protected $currentData;

    protected $currentScope;

    protected $manager;

    protected $parentScopes = array();

    public function __construct(Manager $resourceManager, $currentScope = null)
    {
        $this->resourceManager = $resourceManager;
        $this->currentScope = $currentScope;
    }


    public function embedChildScope($scopeIdentifier, $resource)
    {
        return $this->resourceManager->createData($resource, $scopeIdentifier, $this);
    }
    
    /**
     * Setter for currentData
     *
     * @param mixed $parentScopes Value to set
     *
     * @return self
     */
    public function setCurrentData($currentData)
    {
        $this->currentData = $currentData;
        return $this;
    }

    /**
     * Getter for currentData
     *
     * @return mixed
     */
    public function getCurrentData()
    {
        return $this->currentData;
    }

    /**
     * Getter for currentScope
     *
     * @return mixed
     */
    public function getCurrentScope()
    {
        return $this->currentScope;
    }

    /**
     * Getter for parentScopes
     *
     * @return mixed
     */
    public function getParentScopes()
    {
        return $this->parentScopes;
    }
    
    public function isRequested($checkScopeSegment)
    {
        if ($this->parentScopes) {
            $scopeArray = array_slice($this->parentScopes, 1);
            array_push($scopeArray, $this->currentScope, $checkScopeSegment);
        } else {
            $scopeArray = array($checkScopeSegment);
        }

        $scopeString = implode('.', (array) $scopeArray);

        $checkAgainstArray = $this->resourceManager->getRequestedScopes();

        return in_array($scopeString, $checkAgainstArray);
    }

    /**
     * Push a scope identifier into parentScopes
     *
     * @param string $newScope
     *
     * @return int Returns the new number of elements in the array.
     */
    public function pushParentScope($newScope)
    {
        return array_push($this->parentScopes, $newScope);
    }

    /**
     * Setter for parentScopes
     *
     * @param mixed $parentScopes Value to set
     *
     * @return self
     */
    public function setParentScopes($parentScopes)
    {
        $this->parentScopes = $parentScopes;
        return $this;
    }

    /**
     * Convert the current data for this scope to an array
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'data' => $this->currentData,
        );
    }

    /**
     * Convert the current data for this scope to JSON
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }
}
