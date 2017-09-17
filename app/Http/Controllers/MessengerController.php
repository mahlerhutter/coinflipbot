<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use pimax\FbBotApp;
use pimax\Messages\Message;
use pimax\Messages\ImageMessage;
use pimax\UserProfile;
use pimax\Messages\MessageButton;
use pimax\Messages\StructuredMessage;
use pimax\Messages\MessageElement;
use pimax\Messages\MessageReceiptElement;
use pimax\Messages\Address;
use pimax\Messages\Summary;
use pimax\Messages\Adjustment;
use \App\ReceivedMessages;
use \App\Cities;
use DB;

class MessengerController extends Controller
{
	public function webhook(){

		$local_verify_token = env('WEBHOOK_VERIFY_TOKEN');
		$hub_verify_token = \Input::get('hub_verify_token'); 

		if($local_verify_token == $hub_verify_token) {

			return \Input::get('hub_challenge');

		}
		else return "Ayn, Bad verify token";
	}

	public function webhook_post(){
		//get message input
		$input=\Input::all();
		//get the receipient ----> see  log if you want 2 see how message is received
		
	
		\Log::info(print_r($input,1));
		
		$recipient=$input['entry'][0]['messaging'][0]['sender']['id'];
		$receivedtime=$input['entry'][0]['time'];
		//what sender sent to us

					if (isset($input['entry'][0]['messaging'][0]['message']['text'])){
						//check wether text, pic or payload
						//text
						$receivedmessage=utf8_encode(strtolower($input['entry'][0]['messaging'][0]['message']['text']));
					}
						//sticker or other attachment
					elseif (isset($input['entry'][0]['messaging'][0]['message']['attachments']['0']['payload']['url'])){
						
						$receivedmessage='stickerreceived';
					}
					// Payload
					elseif (isset($input['entry'][0]['messaging'][0]['postback']['payload'])){
						
						$receivedmessage=($input['entry'][0]['messaging'][0]['postback']['payload']);
					}
					    // Payload



		//handle input
		//ist ein keyword enthalten?
		if ((strpos($receivedmessage, "help" )|| $receivedmessage=='?')!= false ){
			$text = 'sendhelpbuttons';
		}
		
		//ob es in array ist
		elseif ($receivedmessage=="Whats your name?" || $receivedmessage=="name"|| $receivedmessage=="name?"|| $receivedmessage=="your name"){
			
			$text = "My name is Coinflipper";
		}

			elseif ($receivedmessage=="where are you from?" || $receivedmessage=="where do you live?"|| $receivedmessage=="location"|| $receivedmessage=="your location"){
			
			$text = "Austria, the beautiful city of Vienna .... (https://www.finchannel.com/~finchannel/world/63856-vienna-tops-mercer-s-19th-quality-of-living-ranking)";
		}
		elseif (in_array($receivedmessage,["hy","hallo","guten Tag","hello",'hi','howdie','howdie','holla','ola','bonjour','Howdy'])){
			
			$text = array("hy","hallo","guten Tag","hello",'hi','howdie','howdie','holla','ola','bonjour','Howdy');
			$rand_key = rand(0,10);
			$text=$text[$rand_key];
		}

		//Check und gib zurück	
		elseif ($receivedmessage=="just a random number?"){
			$text = 'How many possible numbers?.....zero to .....';
		}

		elseif (is_numeric($receivedmessage)){
				$text = rand(0,$receivedmessage);
		}

		elseif ($receivedmessage=="head or tail"){
			$out=rand(0,2);
			if ($out==1){
				
				$text='head';
			}
			else $text = 'tail';
		}

		elseif ($receivedmessage=="shall I do it?"){
			$out=rand(0,2);
			if ($out==1){
				
				$text='yes';
			}
			else $text = 'no';
		}



		else $text="please send me a ?";
		

		//send message


		//Create Bot instance
		$token = env('PAGE_ACCESS_TOKEN');
		$bot = new FbBotApp($token);


		$recipient=$input['entry'][0]['messaging'][0]['sender']['id'];


		
		if ($text == 'sendhelpbuttons')
			{

			$message=new StructuredMessage($recipient, StructuredMessage::TYPE_BUTTON,
                      [
                          'text' => 'How do you want to choose',
                          'buttons' => [
                              new MessageButton(MessageButton::TYPE_POSTBACK, 'just a random number?'),
                              new MessageButton(MessageButton::TYPE_POSTBACK, 'shall I do it? '),
                              new MessageButton(MessageButton::TYPE_POSTBACK, 'head or tail')
                          ]
                      ]);

			$bot->send($message);          
                 
		}

	    

		else 
		{

		$message=new Message($recipient, $text);
		$bot -> send($message);}
		


		//DB::table('received_messages')->insert(
    	//array('message' => $receivedmessage, 'user'=>$recipient));




	}



 }
 
 