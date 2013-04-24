<?php

/*
 * (c) Hubert Perron <hubert.perron@gmail.com>
 *
 */

/**
 * sfWidgetFormDatePicker represents an JQueryUI datepicker.
 */

class sfWidgetFormDatePicker extends sfWidgetFormInputText
{
  /**
   * Constructor.
   * Available options:
   *  * jq_picker_options: An array of key-values used as options by the JQueryUI datepicker
   *  * with_time:         true if the date have to be with time
   *  * time_format:       By default: hh:mm:ss
   *  * date_format:       By default: yy-mm-dd
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidget
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->addOption('jq_picker_options', array());
    $this->addOption('with_time', false);
    $this->addOption('date_format', 'yy-mm-dd');
    $this->addOption('time_format', 'hh:mm:ss');

    $this->default_picker_options = array(
      'showOn'          => 'both',
      'buttonImageOnly' => true,
      'buttonImage'     => '/sfDateTimePickerPlugin/images/date.png',
      'showButtonPanel' => true,
      'changeMonth'     => true,
      'changeYear'      => true,
      'showOtherMonths' => true,
      // Add other datepicker default options here
      // http://jqueryui.com/demos/datepicker/#options
    );
  }

  /**
   * Renders the widget.
   *
   * @param  string $name        The element name
   * @param  string $value       The value displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if(!$this->getAttribute('size')){
      $this->setAttribute('size', $this->getOption('with_time') ? 15 : 9);
    }
    $this->default_picker_options['timeFormat'] = $this->getOption('time_format');
    $this->default_picker_options['dateFormat'] = $this->getOption('date_format');
    $this->default_picker_options['showSecond'] = strstr($this->getOption('time_format'), 'ss') !== false;

    if($timestamp = strtotime($value)){
      $value = strtr($this->getOption('date_format'),
        array(
          'yy' => date('Y', $timestamp),
          'mm' => date('m', $timestamp),
          'dd' => date('d', $timestamp),
          'y'  => date('y', $timestamp),
          'm'  => date('n', $timestamp),
          'd'  => date('j', $timestamp),
        ));
      if($this->getOption('with_time')){
        $value .= ' '.strtr($this->getOption('time_format'),
          array(
            'hh' => date('H', $timestamp),
            'mm' => date('i', $timestamp),
            'ss' => date('s', $timestamp),
            'h'  => date('G', $timestamp),
            'm'  => intval(date('i', $timestamp)),
            's'  => intval(date('s', $timestamp)),
          ));
      }
    }

    // Generate the datePicker javascript code
    $jq_picker_options = array_merge($this->default_picker_options, $this->getOption('jq_picker_options'));
    if(version_compare(PHP_VERSION, '5.3.0') >= 0){
      $jq_picker_options = json_encode($jq_picker_options, JSON_FORCE_OBJECT);
    } else {
      $jq_picker_options = json_encode($jq_picker_options);
    }
    $jq_picker_options = str_replace('\\/', '/', $jq_picker_options); // Fix for: http://bugs.php.net/bug.php?id=49366

    $pickerClass = $this->getOption('with_time') ? 'datetimepicker' : 'datepicker';
    $id = $this->generateId($name);

    $attributes['class'] = 'sfDateTimePicker'.(isset($attributes['class']) ? ' '.$attributes['class'] : '');

    $html = parent::render($name, $value, $attributes, $errors);
    $html .= <<<EOHTML

<script type="text/javascript">
  jQueryPicker(function(){
    jQueryPicker("#$id").$pickerClass($jq_picker_options);
//    jQueryPicker(".ui-datepicker").draggable();
  });
</script>

EOHTML;

    return $html;
  }

  public function getJavaScripts()
  {
    return array(
      '/sfDateTimePickerPlugin/js/jquery/jquery.min.js',
      '/sfDateTimePickerPlugin/js/jquery/ui/jquery-ui.min.js',
      '/sfDateTimePickerPlugin/js/jquery/ui/jquery.ui.datepicker-pl.js',
      '/sfDateTimePickerPlugin/js/jquery/ui/timepicker/jquery-ui-timepicker-addon.js',
    );
  }

  public function getStylesheets()
  {
    return array(
      '/sfDateTimePickerPlugin/js/jquery/ui/smoothness/jquery-ui.css'                  => 'screen',
      '/sfDateTimePickerPlugin/js/jquery/ui/timepicker/jquery-ui-timepicker-addon.css' => 'screen',
    );
  }
}
