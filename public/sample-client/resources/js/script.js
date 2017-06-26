
var webService_Url = "/api/v1/";

var human = {
	"name": "Human",
	"attack": "+2",
	"defense": "+1",
	"strength": "+1",
	"agility": "+2",
	"damage": "1d6",
	"hp": "12"
};

var orc = {
	"name": "Orc",
	"attack": "+1",
	"defense": "0",
	"strength": "+2",
	"agility": "0",
	"damage": "1d8",
	"hp": "20"
}

$(document).ready(function(){
	$('#next-round').css('visibility','hidden');
	$('#start-again').css('visibility','hidden');
	//Set characters to be sent
	var characters = setCharactersArray(human, orc);

	var json = {
		"characters": characters
	};

	$('#humanHp').text(human.hp);
	$('#orcHp').text(orc.hp);


	$("#start").click(function(e){
		e.preventDefault();

		$.ajax({
			url: webService_Url+'initiative',
			type: 'post',
			data: JSON.stringify(json),
			contentType: "application/json; charset=utf-8",
			traditional: true,
			success: function (json) {
				$('#pageResult').html(nl2br(json.data.VerboseResult));
				$('#start').css('visibility','hidden');
				$('#next-round').css('visibility','visible');

				//Send the first attack
				var attacker = json.data.Winner;
				var atkJson = {
					"characters": characters,
					"attacker": attacker
				};

				$.ajax({
					url: webService_Url+'attack',
					type: 'post',
					data: JSON.stringify(atkJson),
					contentType: "application/json; charset=utf-8",
					traditional: true,
					success: function (json) {
						$('input[name="lastAttack"]').attr('value',attacker);

						if( json.data.AttackResult.result == "hit") {
							//reduce HP from the defensor
							if( json.data.Defender.name == "Human"){
								var hp = human.hp - json.data.AttackResult.damage;
								$('#humanHp').html(hp);

							}
							else {
								var hp = orc.hp - json.data.AttackResult.damage;
								$('#orcHp').html(hp);
							}
						}

						$('#pageResult').append(nl2br(json.data.VerboseResult));
					}
				});

				$("#pageContent").animate({
					scrollTop: 1000
				}, 2000);
			}
		});
	});

	$("#next-round").click(function(e){
		e.preventDefault();
		var lastAttacker = $('#lastAttack').val();
		attacker = lastAttacker == "Orc" ? "Human" : "Orc";

		var atkJson = {
			"characters": characters,
			"attacker": attacker
		};

		$.ajax({
			url: webService_Url+'attack',
			type: 'post',
			data: JSON.stringify(atkJson),
			contentType: "application/json; charset=utf-8",
			traditional: true,
			success: function (json) {
				$('input[name="lastAttack"]').attr('value',attacker);

				if( json.data.AttackResult.result == "hit") {
					//reduce HP from the defensor
					if( json.data.Defender.name == "Human"){
						var hp =  $('#humanHp').text();
						hp = hp - json.data.AttackResult.damage;
						$('#humanHp').html(hp);
						if(hp <= 0 ){
							alert("Human is dead!");
							$('#next-round').css('visibility','hidden');
							$('#start-again').css('visibility','visible');
						}

					}
					else {
						var hp =  $('#orcHp').text();
						hp = hp - json.data.AttackResult.damage;
						$('#orcHp').html(hp);
						if(hp <= 0 ){
							alert("Orc is dead!");
							$('#next-round').css('visibility','hidden');
							$('#start-again').css('visibility','visible');
						}
					}
				}

				$('#pageResult').html(nl2br(json.data.VerboseResult));

				$("#pageContent").animate({
					scrollTop: 1000
				}, 2000);
			}
		});

		$("#pageContent").animate({
			scrollTop: 1000
		}, 2000);
	});

	$("#start-again").click(function(e){

		$('#humanHp').text(human.hp);
		$('#orcHp').text(orc.hp);

		$('#start').css('visibility','visible');
		$('#start-again').css('visibility','hidden');
		$('#pageResult').html('');

	});

});


function setCharactersArray(human, orc) {

	var characters =  [
			human,
			orc
		];

	return characters;
}

function nl2br (str, is_xhtml) {
	var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
	return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
}