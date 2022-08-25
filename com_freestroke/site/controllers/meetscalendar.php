<?php
/**
 * @version     1.0.0
 * @package     com_freestroke
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      O.Minderaa <ominderaa@gmail.com> - http://
 */

// No direct access.
defined('_JEXEC') or die;

jimport ( 'joomla.application.component.controller' );

/**
 * Meets list controller class.
 */
class FreestrokeControllerMeetscalendar extends JControllerLegacy
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'Meetscalendar', $prefix = 'FreestrokeModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => false));
		return $model;
	}
	
	/**
	 * import members and program from lxf file
	 *
	 * @return void boolean
	 */
	function lxfimportinvitation() {
		$app = JFactory::getApplication();
	
		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_freestroke/tables');
	
		if ($file = $app->input->files->get('importfile')) {
			$handle = fopen($file ['tmp_name'], 'r');
			if (! $handle) {
				$app->enqueueMessage(JText::_('Cannot open uploaded file.'));
				return;
			}
			fclose($handle);
				
			// parse the lenex file
			$xmlFile = $this->extractXMLfromArchive($file ['tmp_name']);
			$lenexXml = simplexml_load_file($xmlFile);
			if ($lenexXml === FALSE) {
				$app->enqueueMessage("Dit bestand wordt niet herkend. Is het wel een LXF bestand?");
			} else {
				require_once JPATH_COMPONENT . '/logic/lenexparser.php';
				$parser = new FreestrokeLenexParser();
				$lenex = $parser->parse($lenexXml);

				$meet = $lenex->meets[0];
				$name = $meet->name;
				$mindate = $meet->sessions[0]->date;
				$city = $meet->city;
				
				require_once JPATH_COMPONENT . '/helpers/meets.php';
				$meetObject = FreestrokeMeetsHelper::findByNameCityAndDate($name, $city, $mindate);
				if($meetObject) {
					require_once JPATH_COMPONENT . '/logic/invitationreader.php';
					$reader = new FreestrokeInvitationReader();
					$hasinvite = $reader->process($lenex, $meetObject->id);
		
					// Flush the data from the session.
// 					$app->setUserState('com_freestroke.edit.meet.data', null);
				} else {
					$app->enqueueMessage("Deze wedstrijd is niet gevonden. Selecteer zelf eerst de juiste wedstrijd.");
				}
			}
		}
		$this->setRedirect(JRoute::_('index.php?option=com_freestroke&view=meetscalendar'));
	}
	
	/**
	 * import meet entries from lxf file
	 *
	 * @return void boolean
	 */
	function lxfimportentries() {
		$app = JFactory::getApplication();
	
		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_freestroke/tables');
	
		$overwriteprogram = $app->input->get('overwriteprogram');
	
		if ($file = $app->input->files->get('importfile')) {
			$handle = fopen($file ['tmp_name'], 'r');
			if (! $handle) {
				$app->enqueueMessage(JText::_('Cannot open uploaded file.'));
				return;
			}
			fclose($handle);
				
			// parse the lenex file
			$xmlFile = $this->extractXMLfromArchive($file ['tmp_name']);
			$lenexXml = simplexml_load_file($xmlFile);
			if ($lenexXml === FALSE) {
				$app->enqueueMessage("Dit bestand wordt niet herkend. Is het wel een LXF bestand?");
			} else {
				require_once JPATH_COMPONENT . '/logic/lenexparser.php';
				$parser = new FreestrokeLenexParser();
				$lenex = $parser->parse($lenexXml);
	
				$meet = $lenex->meets[0];
				$name = $meet->name;
				$mindate = $meet->sessions[0]->date;
				$city = $meet->city;

				require_once JPATH_COMPONENT . '/helpers/meets.php';
				$meetObject = FreestrokeMeetsHelper::findByNameCityAndDate($name, $city, $mindate);
				if($meetObject) {
					if (! empty($overwriteprogram)) {
						require_once JPATH_COMPONENT . '/logic/invitationreader.php';
						$reader = new FreestrokeInvitationReader();
						$reader->process($lenex, $meetObject->id);
					}
					
					require_once JPATH_COMPONENT . '/logic/entriesreader.php';
					$componentParams = &JComponentHelper::getParams('com_freestroke');
					$clubcode = $componentParams->get('associationcode', null);
					if ($clubcode != null && strlen($clubcode) > 0) {
						$reader = new FreestrokeEntriesReader();
						$hasentries = $reader->process($lenex, $meetObject->id, $clubcode);
					} else {
						$app->enqueueMessage("De KNZB Club code is niet ingevuld. Neem contact op met de website beheerder.");
					}
				} else {
					$app->enqueueMessage("Deze wedstrijd is niet gevonden. Selecteer zelf eerst de juiste wedstrijd.");
				}
					
				// Flush the data from the session.
// 				$app->setUserState('com_freestroke.edit.meet.data', null);
			}
		}
	
		$this->setRedirect(JRoute::_('index.php?option=com_freestroke&view=meetscalendar'));
	}
	
	/**
	 * import results from lxf file
	 *
	 * @return void boolean
	 */
	function lxfimportresults() {
		$app = JFactory::getApplication();
	
		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_freestroke/tables');
	
		$overwriteprogram = $app->input->get('overwriteprogram');
		$deleteresults = $app->input->get('deleteresults');
		$deletefirst = false;
		if (! empty($deleteresults)) {
			$deletefirst = true;
		}
	
		if ($file = $app->input->files->get('importfile')) {
			$handle = fopen($file ['tmp_name'], 'r');
			if (! $handle) {
			    $app->enqueueMessage(JText::_('Cannot open uploaded file.'));
			    return;
			}
			fclose($handle);
				
			// parse the lenex file
			$xmlFile = $this->extractXMLfromArchive($file ['tmp_name']);
			$lenexXml = simplexml_load_file($xmlFile);
			if ($lenexXml === false) {
				$app->enqueueMessage("Dit bestand wordt niet herkend. Is het wel een LXF bestand?");
			} else {
				require_once JPATH_COMPONENT . '/logic/lenexparser.php';
				$parser = new FreestrokeLenexParser();
				$lenex = $parser->parse($lenexXml);
				
				$meet = $lenex->meets[0];
				$name = $meet->name;
				$mindate = $meet->sessions[0]->date;
				$city = $meet->city;
				
				require_once JPATH_COMPONENT . '/helpers/meets.php';
				$countMeets = FreestrokeMeetsHelper::countByNameCityAndDate($name, $city, $mindate);
				if($countMeets == 1 ) {
					$meetObject = FreestrokeMeetsHelper::findByNameCityAndDate($name, $city, $mindate);
				}
				else {
					$countMeets = FreestrokeMeetsHelper::countByCityAndDate($city, $mindate);
					if($countMeets == 1 ) {
						$meetObject = FreestrokeMeetsHelper::findByCityAndDate($city, $mindate);
					} else {
						if($countMeets > 1) {
							$app->enqueueMessage("Meerdere wedstrijden komen in aanmerking voor dit bestand.");
						} else {
								$app->enqueueMessage("Geen wedstrijd gevonden voor dit bestand.");
						}
					}
				}
				if($meetObject) {
					if (! empty($overwriteprogram)) {
						require_once JPATH_COMPONENT . '/logic/invitationreader.php';
						$reader = new FreestrokeInvitationReader();
						$hasinvite = $reader->process($lenex, $meetObject->id);
					}
						
					require_once JPATH_COMPONENT . '/logic/resultsreader.php';
					$componentParams = &JComponentHelper::getParams('com_freestroke');
					$clubcode = $componentParams->get('associationcode', null);
					$clubname = $componentParams->get('clubname', null);
					if ($clubcode != null && strlen($clubcode) > 0) {
						$reader = new FreestrokeResultsReader();
						$hasresults = $reader->process($lenex, $meetObject->id, $clubcode, $clubname, $deletefirst);
					}

					// Flush the data from the session.
// 					$app->setUserState('com_freestroke.edit.meet.data', null);
				}
			}
		}
	
		$this->setRedirect(JRoute::_('index.php?option=com_freestroke&view=meetscalendar'));
	}
	
	/**
	 * Extract the lenex xml file from the archive
	 *
	 * @param unknown $archive
	 * @return string
	 */
	private function extractXMLfromArchive($archive) {
		$za = new ZipArchive();
		$zaresult = $za->open($archive);
		if ($zaresult === TRUE) {
			for($i = 0; $i < $za->numFiles; $i ++) {
				$stat = $za->statIndex($i);
				JLog::add('Uploaded lenex archive contains file ' . basename($stat ['name']), JLog::WARNING, 'com_freestroke');
	
				if (preg_match('/.?\.lef$/', basename($stat ['name'])) == 1) {
					$filename = dirname($archive) . DIRECTORY_SEPARATOR . basename($stat ['name']);
					$za->extractTo(dirname($archive), basename($stat ['name']));
				}
			}
		} else {
			if ($zaresult == ZipArchive::ER_NOZIP) {
				$filename = $archive;
			} else {
				$filename = null;
			}
		}
		return $filename;
	}
}
