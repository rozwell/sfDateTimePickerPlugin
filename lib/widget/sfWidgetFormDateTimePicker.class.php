<?php

/*
 * (c) Hubert Perron <hubert.perron@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormDatePicker represents an JQueryUI datepicker.
 */

class sfWidgetFormDateTimePicker extends sfWidgetFormDatePicker
{
  /**
   * Constructor.
   *
   * Available options:
   *
   *  * jq_picker_options: An array of key-values used as options by the JQueryUI datepicker
   *  * with_time:         true if the date have to be with time
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidget
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->setOption('with_time', true);
    $this->setOption('time_format', 'hh:mm');
  }
}
