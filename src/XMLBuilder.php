<?php

namespace Yudu\Publisher;

use DOMDocument;

/**
 * XML Builder
 *
 * @package   yudu/publisher
 * @author    Andrew James Bibby
 * @license   MIT License
 * @link      https://github.com/YUDUcreative/Publisher-REST-API-Library
 *
 * This class assists in building up XML strings in the format expected when
 * making POST / PUT requests to the YUDU Publisher REST API.
 */
class XMLBuilder {

    /**
     * Create Reader
     *
     * Builds expected XML for creating a reader
     *
     * @param $data
     * @return string
     */
    public static function createReader($data)
    {
        $dom = new DomDocument();

        $reader = $dom->createElementNS('http://schema.yudu.com', "reader");

        $dom->appendChild($reader);

        foreach($data as $key => $value)
        {
            $element = $dom->createElement($key);
            $element->appendChild($dom->createTextNode($value));
            $reader->appendChild($element);
        }

        return $dom->saveXML();
    }

    /**
     * Create Edition
     *
     * Builds expected XML for creating an Edition
     *
     * @param $data
     * @return string
     */
    public static function createEdition($data)
    {
        $dom = new DomDocument();

        $edition = $dom->createElementNS('http://schema.yudu.com', "editionState");

        $dom->appendChild($edition);

        foreach($data as $key => $value)
        {
            if($key === "targetState"){
                $targetState = $dom->createElement('targetState');
                foreach($value as $platform => $state)
                {
                    $element = $dom->createElement($platform);
                    $element->appendChild($dom->createTextNode($state));
                    $targetState->appendChild($element);
                }
                $edition->appendChild($targetState);
            } else {
                $element = $dom->createElement($key);
                $element->appendChild($dom->createTextNode($value));
                $edition->appendChild($element);
            }
        }

        return $dom->saveXML();
    }

    /**
     * Update Reader
     *
     * Builds expected XML for updating a reader
     *
     * @param $data
     * @param $id
     * @return string
     */
    public static function updateReader($id, $data)
    {
        $dom = new DomDocument();

        $reader = $dom->createElementNS('http://schema.yudu.com', "reader");

        $reader->setAttribute("id", $id);

        $dom->appendChild($reader);

        foreach($data as $key => $value)
        {
            $element = $dom->createElement($key);
            $element->appendChild($dom->createTextNode($value));
            $reader->appendChild($element);
        }

        return $dom->saveXML();
    }

    /**
     * Create Permission
     *
     * Builds expected XML for creating a permission
     *
     * @param $data
     * @return string
     */
    public static function createPermission($data)
    {
        $dom = new DomDocument();

        $permission = $dom->createElementNS('http://schema.yudu.com', "permission");

        $dom->appendChild($permission);

        foreach($data as $key => $value)
        {
            $element = $dom->createElement($key);
            $element->setAttribute("id", $value);
            $permission->appendChild($element);
        }

        return $dom->saveXML();
    }

    /**
     * Update Permission
     *
     * Builds expected XML for updating a permission
     *
     * @param $data
     * @return string
     */
    public static function updatePermission($id, $data)
    {
        $dom = new DomDocument();

        $permission = $dom->createElementNS('http://schema.yudu.com', "permission");

        $permission->setAttribute("id", $id);

        $dom->appendChild($permission);

        foreach($data as $key => $value)
        {
            $element = $dom->createElement($key);
            $element->appendChild($dom->createTextNode($value));
            $permission->appendChild($element);
        }

        return $dom->saveXML();
    }

    /**
     * Create Subscription Period
     *
     * Builds expected XML for creating a subscription period
     *
     * @param $data
     * @return string
     */
    public static function createSubscriptionPeriod($data)
    {
        $dom = new DomDocument();

        $subscriptionPeriod = $dom->createElementNS('http://schema.yudu.com', "subscriptionPeriod");

        $dom->appendChild($subscriptionPeriod);

        // Add required reader element
        $reader = $dom->createElement('reader');
        $reader->setAttribute("id", $data['reader']);
        $subscriptionPeriod->appendChild($reader);

        // Add required subscription element
        $subscription = $dom->createElement('subscription');
        $subscription->setAttribute("id", $data['subscription']);
        $subscriptionPeriod->appendChild($subscription);

        // Add required startDate element
        $startDate = $dom->createElement('startDate');
        $startDate->appendChild($dom->createTextNode($data['startDate']));
        $subscriptionPeriod->appendChild($startDate);

        // Add optional expiry date
        if($data['expiryDate']){
            $expiryDate = $dom->createElement('expiryDate');
            $expiryDate->appendChild($dom->createTextNode($data['expiryDate']));
            $subscriptionPeriod->appendChild($expiryDate);
        }

        return $dom->saveXML();
    }

    /**
     * Update Subscription Period
     *
     * Builds expected XML for updating a subscription period
     *
     * @param $data
     * @return string
     */
    public static function updateSubscriptionPeriod($id, $data)
    {
        $dom = new DomDocument();

        $subscriptionPeriod = $dom->createElementNS('http://schema.yudu.com', "subscriptionPeriod");

        $subscriptionPeriod->setAttribute("id", $id);

        $dom->appendChild($subscriptionPeriod);

        foreach($data as $key => $value)
        {
            $element = $dom->createElement($key);
            $element->appendChild($dom->createTextNode($value));
            $subscriptionPeriod->appendChild($element);
        }

        return $dom->saveXML();
    }

    /**
     * Authenticate Password
     *
     * Builds expected XML for Authenticating a readers password
     *
     * @param $password
     * @return string
     */
    public static function authenticatePassword($password)
    {
        $dom = new DomDocument();

        $authentication = $dom->createElementNS('http://schema.yudu.com', 'authentication');

        $dom->appendChild($authentication);

        $pass = $dom->createElement('password');
        $pass->appendChild($dom->createTextNode($password));
        $authentication->appendChild($pass);

        return $dom->saveXML();
    }

    /**
     * Create Token
     *
     * Builds expected XML for creating an SSO token
     *
     * @param $userId
     * @return string
     */
    public static function createToken($userId)
    {
        $dom = new DomDocument();

        $authToken = $dom->createElementNS('http://schema.yudu.com', "authToken");

        $dom->appendChild($authToken);

        $key = $dom->createElement('key');
        $key->appendChild($dom->createTextNode($userId));
        $authToken->appendChild($key);

        return $dom->saveXML();
    }

    /**
     * Targeted Notification
     *
     * Builds expected XML for sending a targeted notification
     *
     * @param $nodeId
     * @param $title
     * @param $message
     * @param array $thirdPartySubscribers
     * @param array $subscribers
     * @param string $priority
     * @param bool $disableSound
     * @return string
     */
    public static function targetedNotification($nodeId, $title, $message, $subscribers = [], $thirdPartySubscribers = [], $priority = "DEFAULT", $disableSound = false)
    {
        $dom = new DomDocument();

        $targetedNotification = $dom->createElementNS('http://schema.yudu.com', "targetedNotification");

        $dom->appendChild($targetedNotification);

        $_nodeId = $dom->createElement('nodeId');
        $_nodeId->appendChild($dom->createTextNode($nodeId));
        $targetedNotification->appendChild($_nodeId);

        $_message = $dom->createElement('message');
        $_message->appendChild($dom->createTextNode($message));
        $targetedNotification->appendChild($_message);

        $_title = $dom->createElement('title');
        $_title->appendChild($dom->createTextNode($title));
        $targetedNotification->appendChild($_title);

        $_priority = $dom->createElement('notificationPriority');
        $_priority->appendChild($dom->createTextNode($priority));
        $targetedNotification->appendChild($_priority);

        $_disableSound = $dom->createElement('disableSound');
        $_disableSound->appendChild($dom->createTextNode($disableSound));
        $targetedNotification->appendChild($_disableSound);

        $_subscribers = $dom->createElement('subscribers');

        foreach($subscribers as $subscriber)
        {
            $element = $dom->createElement('subscriberUsername');
            $element->appendChild($dom->createTextNode($subscriber));
            $_subscribers->appendChild($element);
        }

        foreach($thirdPartySubscribers as $thirdPartySubscriber)
        {
            $element = $dom->createElement('thirdPartySubscriberToken');
            $element->appendChild($dom->createTextNode($thirdPartySubscriber));
            $_subscribers->appendChild($element);
        }

        $targetedNotification->appendChild($_subscribers);

        return $dom->saveXML();
    }

    /**
     * Create Category
     *
     * Builds expected XML for creating a category
     *
     * @param $data
     * @return string
     */
    public static function createCategory($data)
    {
        $dom = new DomDocument();

        $category = $dom->createElementNS('http://schema.yudu.com', "category");

        $dom->appendChild($category);

        foreach($data as $key => $value)
        {
            $element = $dom->createElement($key);
            $element->appendChild($dom->createTextNode($value));
            $category->appendChild($element);
        }

        return $dom->saveXML();
    }

    /**
     * Create Category Edition
     *
     * Builds expected XML for creating a categoryEdition
     *
     * @param $data
     * @return string
     */
    public static function createCategoryEdition($data)
    {
        $dom = new DomDocument();

        $categoryEdition = $dom->createElementNS('http://schema.yudu.com', "categoryEdition");

        $dom->appendChild($categoryEdition);

        foreach($data as $key => $value)
        {
            $element = $dom->createElement($key);
            $element->appendChild($dom->createTextNode($value));
            $categoryEdition->appendChild($element);
        }

        return $dom->saveXML();
    }

}


