<!DOCTYPE html>
	<head>
	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<style>
		body,html
		{
		width:100%;
		height:100%;
		margin:0px;
		}
		div#code_preview
		{
		width:100%;
		height:75%;
		float:left;
		}
		input#code_textinput
		{
		width:100%;
		height:40px;
		position:fixed;
		bottom:0px;
		left:0px;
		margin:0px;
		padding:0px;
		border:none;
		line-height:40px;
		text-indent:5px;
		background-color:#292929;
		color:#eee;
		font-size:17px;
		outline:none;
		}
		</style>
		<style id="customCSS"></style>
	</head>
	<body>
		<div id="code_preview"></div>
		<input id="code_textinput">
		<script>
		$(document).ready(function()
		{
			//capturing
			$('#code_textinput').keypress(function(e)
			{
				if(e.which==13)
				{
					input=$('#code_textinput').val();
					doCommand(input);
				}
			});
		});
		function doCommand(input)
		{
			inputparts=input.split(' ');
			command=inputparts[0];
			if(command=="create")//create tag+selector+name (!opt css:css;) (!opt [before,into,after]:tag+selector+name)
			{
				element=inputparts[1];
				
				inf=getelementInf(element);
				
				temp=document.createElement(inf[0]);
				
				if(inf[1]='id')
				{
					temp.id=inf[2];
				}
				else if(inf[1]='class')
				{
					temp.class=inf[2];
				}
				else
				{
					kill('I honestly have no idea how this could even happen... Seriously');
				}
				
				if(typeof inputparts[2]!=='undefined')
				{
					//there is css to apply
					css=inputparts[2];
					customCSS=$('style#customCSS').html();
					if(customCSS=='')
					{
						console.log('customCSS empty');
						$('style#customCSS').append(element+'{'+css+'}');
					}
					else
					{
						if(customCSS.indexOf(element)!=-1)
						{
							//the element is already in the css
							console.log('already in css');
						}
						else
						{
							//the element is not already in the css
							$('style#customCSS').append(element+'{'+css+'}');
							console.log('added to css');
						}
					}
				}
				if(typeof inputparts[3]!=='undefined')
				{
					elementLocation=inputparts[3];
					relativeLocation=elementLocation.substr(0,elementLocation.indexOf(':'));
					relativeElement=elementLocation.substr(elementLocation.indexOf(':')+1,elementLocation.length);
					
					if(relativeLocation=='before')//before
					{
						$(relativeElement).before(temp);
					}
					else if(relativeLocation=='prepend')//prepend
					{
						console.log('k');
						$(relativeElement).prepend(temp);
					}
					else if(relativeLocation=='append')//append
					{
						$(relativeElement).append(temp);
					}
					else if(relativeLocation=='after')//after
					{
						$(relativeElement).after(temp);
					}
					else
					{
						kill('The given location string syntax was not recognized or was incorrect');
					}
				}
				else
				{
					//if the location is not specified,
					//it will auto go to the root of the document (div#code_preview).
					$('#code_preview').append(temp);
				}
			}
			else if(command=="move")//move tag+selector+name [before,into,after]:tag+selector+name
			{
				//TODO
			}
			else if(command=="focus")//focus tag+selector+name
			{
				focusOn=element=inputparts[1];
				//TODO
			}
			else if(command=="set")//set variableName variableValue
			{
				variableName=inputparts[1];
				variableValue=inputparts[2];
				window[variableName]=variableValue;
			}
			else if(command=="editCSS")//editCSS tag+selector+name (css:css;)
			{
				element=inputparts[1];
				css=inputparts[2];
				customCSS=$('style#customCSS').html();
				if(customCSS=='')
				{
					console.log('customCSS empty');
				}
				else
				{
					if(customCSS.indexOf(element)!=-1)
					{
						//the element is already in the css
						$('style#customCSS').append(element+'{'+css+'}');
						console.log('edited css');
					}
					else
					{
						//the element is not already in the css
						console.log('not in css');
					}
				}
			}
			else if(command=="addCSS")//addCSS tag+selector+name (css:css;)
			{
				element=inputparts[1];
				css=inputparts[2];
				customCSS=$('style#customCSS').html();
				if(customCSS=='')
				{
					console.log('customCSS empty');
					$('style#customCSS').append(element+'{'+css+'}');
				}
				else
				{
					if(customCSS.indexOf(element)!=-1)
					{
						//the element is already in the css
						console.log('already in css');
					}
					else
					{
						//the element is not already in the css
						$('style#customCSS').append(element+'{'+css+'}');
						console.log('added to css');
					}
				}
			}
			else if(command=="removeCSS")//removeCSS tag+selector+name
			{
				//TODO
			}
			else if(command=="remove")//remove tag+selector+name
			{
				element=inputparts[1];
				$(element).remove();
			}
			else if(command=="unset")//unset variableName
			{
				window[variableName]=null;
				delete window[variableName];
			}
			else
			{
				kill('Command/User input was not recognized by this program.');
			}
		}
		function kill(input)
		{
			console.log('[ERROR]: '+input);
			throw new Error();
		}
		function getelementInf(input)
		{
			if(input.indexOf('#')!=-1)
			{
				selector='id';
				tag=input.substr(0,input.indexOf('#'));
				name=input.substr(input.indexOf('#')+1,input.length);
			}
			else if(input.indexOf('.')!=-1)
			{
				selector='class';
				tag=input.substr(0,input.indexOf('.'));
				name=input.substr(input.indexOf('.')+1,input.length);
			}
			else
			{
				kill('Provided input does not have a selector associated with it.');
			}
			return [tag,selector,name];
		}
		</script>
	</body>
</html>