<?php

$plain = '
    <all flag="duration">

      <all assert="no_beginning_time">
        <attr valuePattern="[1-9][0-9]*|0" assert="no_start_hour">start_hour</attr>
        <attr valuePattern="[1-5]?[0-9]" assert="no_start_minute">start_minute</attr>
        <attr valuePattern="[1-5]?[0-9]" assert="no_start_second">start_second</attr>
      </all>

      <one assert="no_termination_time">

        <all flag="ending_time">
          <attr valuePattern="[1-9][0-9]*|0" assert="no_end_hour">end_hour</attr>
          <attr valuePattern="[1-5]?[0-9]" assert="no_end_minute">end_minute</attr>
          <attr valuePattern="[1-5]?[0-9]" assert="no_end_second">end_second</attr>
        </all>

        <all flag="duration_time">
          <attr valuePattern="[1-9][0-9]*|0" assert="no_duration_hour">duration_hour</attr>
          <attr valuePattern="[1-5]?[0-9]" assert="no_duration_minute">duration_minute</attr>
          <attr valuePattern="[1-5]?[0-9]" assert="no_duration_second">duration_second</attr>
        </all>

      </one>

    </all>
';

$single = '<?xml version="1.0" encoding="UTF-8"?>
    <pattern>
      <key_attr attr="action">

        <!--// "id" or "file_name" must be used -->
        <one forValue="delete">
          <attr>id</attr>
          <attr>file_name</attr>
        </one>

        <!--// "new_file" is mandatory and "new_name" is optional attribute -->
        <all forValue="create">
          <attr>new_file</attr>
          <opt>
            <attr>new_name</attr>
          </opt>
        </all>

        <!--// "new_name" is now mandatory with one of "id" or "old_name" -->
        <all forValue="rename">
          <one>
            <attr>id</attr>
            <attr>old_name</attr>
          </one>
          <attr>new_name</attr>
        </all>
        
        <!--// "id" is mandatory argument for "report" action. -->
        <attr forValue="report">id</attr>
        <!--        
        //  Consider equivalent but slightly clumsy statement:
        //
        //  <all forValue="report">
        //    <attr>id</attr>
        //  </all>
        -->

      </key_attr>
    </pattern>
';

$multiple = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
    <!DOCTYPE patterns SYSTEM "TreePattern053.dtd">
    <patterns>
      <pattern>
        <all>
          <one>

            <attr flag="show_help">help</attr>
            <attr flag="show_version">version</attr>

            <some flag="perform_action">

              <!-- ignote option and set assert if present -->
              <alias attr="u" validationAssert="deprecated" valuePattern="/(?!)/" />

              <!-- translate long options to its short versions -->
              <alias attr="number-nonblank">
                <attr>b</attr>
              </alias>

              <alias attr="show-ends">
                <attr>E</attr>
              </alias>

              <alias attr="number">
                <attr>n</attr>
              </alias>

              <alias attr="squeeze-blank">
                <attr>s</attr>
              </alias>

              <alias attr="show-tabs">
                <attr>T</attr>
              </alias>

              <alias attr="show-nonprinting">
                <attr>v</attr>
              </alias>

              <!-- expand aggregative options -->
              <alias attr="show-all">
                <attr>v</attr>
                <attr>E</attr>
                <attr>T</attr>
              </alias>
              
              <alias attr="A">
                <attr>v</attr>
                <attr>E</attr>
                <attr>T</attr>
              </alias>

              <alias attr="e">
                <attr>v</attr>
                <attr>E</attr>
              </alias>

              <alias attr="t">
                <attr>v</attr>
                <attr>T</attr>
              </alias>

              <!-- allowed short options -->
              <attr>b</attr>
              <attr>E</attr>
              <attr>n</attr>
              <attr>s</attr>
              <attr>T</attr>
              <attr>v</attr>
            </some>  

          </one>
        </all>
      </pattern>
    </patterns>
';

require_once 'bootstrap.test.php';
require_once 'PHP/MapFilter/TreePattern.php';

$plainFilter = new MapFilter ( MapFilter_TreePattern_Xml::load ( $plain ) );
$singleFilter = new MapFilter ( MapFilter_TreePattern_Xml::load ( $single ) );
$multipleFilter = new MapFilter ( MapFilter_TreePattern_Xml::load ( $multiple ) );

$plainFilter->fetchResult ();
$singleFilter->fetchResult ();
$multipleFilter->fetchResult ();
