<?php
/**
 * Tatoeba Project, free collaborative creation of multilingual corpuses project
 * Copyright (C) 2016 Gilles Bedel
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class AudioController extends AppController
{
    public $name = 'Audio';

    public $uses = array(
        'Audio',
        'User',
    );

    public $components = array(
    );

    public $helpers = array(
        'Pagination',
        'Languages',
    );

    public $paginate = array(
        'contain' => array(
            'User' => array('username'),
            'Sentence' => array('Transcription')
        ),
        'limit' => 100,
        'order' => 'Audio.modified DESC'
    );

    public function beforeFilter()
    {
        parent::beforeFilter();

        $this->Auth->allowedActions = array(
            'of',
            'index',
        );
    }

    public function index($lang = null) {
        $conditions = array();
        if (LanguagesLib::languageExists($lang)) {
            $conditions['Sentence.lang'] = $lang;
            $this->set(compact('lang'));
        }
        $sentencesWithAudio = $this->paginate('Audio', $conditions);
        $this->set(compact('sentencesWithAudio'));
        $this->set(array('stats' => $this->Audio->getAudioStats()));
    }

    public function of($username) {
        $userId = $this->User->getIdFromUsername($username);
        if ($userId) {
            $sentencesWithAudio = $this->paginate('Audio', array(
                'Audio.user_id' => $userId,
            ));
            $this->set(compact('sentencesWithAudio'));
        }
        $this->set(compact('username'));
    }
}
?>
