<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014
 * @package yii2-slider
 * @version 1.0.0
 */

namespace kartik\slider;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * An extended slider input for Bootstrap 3 based 
 * on bootstrap-slider plugin. 
 *
 * @see https://github.com/seiyria/bootstrap-slider
 * @see http://www.eyecon.ro/bootstrap-slider/
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class Slider extends \kartik\widgets\InputWidget
{
    const TYPE_GREY = '#bababa';
    const TYPE_PRIMARY = '#428bca';
    const TYPE_INFO = '#5bc0de';
    const TYPE_SUCCESS = '#5cb85c';
    const TYPE_DANGER = '#d9534f';
    const TYPE_WARNING = '#f0ad4e';
    
    /**
     * Background color for the slider handle
     */
    public $handleColor;

    /**
     * Background color for the slider selection
     */
    public $sliderColor;
    
    private $_isDisabled = false;
    
    /**
     * Initializes the widget
     */
    public function init()
    {
        parent::init();
        Html::addCssClass($this->options, 'form-control');
        // Initialize value
        if (!empty($this->value)) {
            $this->pluginOptions['value'] = $this->value;
        }
        else {
            $this->pluginOptions['value'] = null;
        }
        if (is_array($this->value)) {
            $this->value = implode(':', $this->value);
        }
        echo $this->getInput('textInput');
        $this->_isDisabled = ((!empty($this->options['disabled']) && $this->options['disabled']) || 
            (!empty($this->options['readonly']) && $this->options['readonly']));
        $this->registerAssets();
    }
    
    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        SliderAsset::register($view);
        $id = "$('#" . $this->options['id'] . "')";
        
        
        
        // Initialize if disabled
        if ($this->_isDisabled) {
            $this->pluginOptions['enabled'] = false;
        }
        
        $this->pluginOptions['id'] = $this->options['id'] . '-slider';
        $this->registerPlugin('slider');
        $cssStyle = null;
        
        // register CSS styles
        if (!empty($this->handleColor) && !$this->_isDisabled) {
            $isTriangle = (!empty($this->pluginOptions['handle']) && $this->pluginOptions['handle'] == 'triangle');
            $cssStyle = $this->getCssColor('handle', $this->handleColor, $isTriangle);
        }
        if (!empty($this->sliderColor) && !$this->_isDisabled) {
            $cssStyle .= $this->getCssColor('selection', $this->sliderColor);
        }
        if ($cssStyle != null && !$this->_isDisabled) {
            $view->registerCss($cssStyle);
        }
        
        // Trigger the change event on slider stop, so that client validation
        // is triggered for yii active fields
        $view->registerJs("{$id}.on('slideStop', function(){{$id}.trigger('change')});");
    }
    
    /**
     * Gets the css background style for a slider element type
     */
    protected function getCssColor($type, $color, $isTriangle = false)
    {
        $feature = $isTriangle ? 'border-bottom-color' : 'background';
        return "#" . $this->pluginOptions['id'] . " .slider-{$type}{{$feature}:{$color}}";
    }
}