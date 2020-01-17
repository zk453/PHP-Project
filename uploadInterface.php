<?php
include("sessionHandler.php");
loginCheck();
?>
<!DOCTYPE html>
<html>
<head>
</head>
<meta charset="UTF-8">
<title>Ανέβασμα Αρχείων</title>
<script
        src="https://code.jquery.com/jquery-3.3.1.js"
        integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
<script src="node_modules/cropperjs/dist/cropper.js"></script>
<script src="node_modules/jquery-cropper/dist/jquery-cropper.js"></script>
<link href="node_modules/cropperjs/dist/cropper.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/imageUploadClean.css">
<link rel="stylesheet" type="text/css" href="css/defaultClean.css">
<body>
<div class="topbar">
    <div class="userInfo">
        <?php
            echo "Σύνδεση ως: ".$_SESSION['username'];
        ?>
    </div>
	<form action="logout.php" method="get">
		<input type="submit" class="normalButton orangeGradient" value="Log Out" name="Submit" id="frm1_submit" />
	</form>
</div>
<div class="imgEditorOverlay" id="imgEditorOverlay">
    <div class="imgEditor outerDropShadow">
        <div class="container" style="width: 80%; height: 100%;float:left;">
            <img id="cropImage" src="">
        </div>
        <div class="imgSideBar">
            <button class="closeButton">X</button>
            <table>
                <tr>
                    <th>Info:</th>
                    <th>X</th>
                    <th>Y</th>
                </tr>
                <tr>
                    <td>Resolution</td>
                    <td id="originalResolutionx"></td>
                    <td id="originalResolutiony"></td>
                </tr>
                <tr>
                    <th colspan="3">Crop Info:</th>
                </tr>
                <tr>
                    <td>Top Left Corner:</td>
                    <td id="topLeftx"></td>
                    <td id="topLefty"></td>
                </tr>
                <tr>
                    <td>Dimensions:</td>
                    <td id="cropWidth"></td>
                    <td id="cropHeight"></td>
                </tr>
            </table>
            <button class="nextButton smallbutton"></button>
            <button class="prevButton smallbutton"></button>
            <br>
            <button class="rotateLButton smallbutton">↺</button>
            <button class="rotateRButton smallbutton">↻</button>
            <br>
            <br>
            <button class="wideAspectRatio smallbutton">Wide</button>
            <button class="tallAspectRatio smallbutton">Tall</button>
            <br>
            <br>
            <button class="fitWidthButton smallbutton">Fit Width</button>
            <button class="fitHeightButton smallbutton">Fit Height</button>
            <br>
            <br>
            <button class="applyNextButton smallbutton">Apply and go Next</button>
            <button class="applyPrevButton smallbutton">Apply and go Prev</button>
        </div>
    </div>
</div>





<div class='editorInterface'">
	<div class="sidebar">
		<img alt="company Logo" src="/resources/images/company name.png">
		<div class="imageUpload">
			<button id='showUploadMenuButton' class='grayBorderButton'><img src="resources/images/folderIcon.png"> Επιλογή αρχείων</button>
			
			<div class="uploadForms">
				<form  action="submit.php" method="post" id="upload_form">
					<div class="wrapper grayBorderButton">
						<div class="fileUploadButton">
							<img src="resources/images/folderIcon.png">
							<div class="defaultText">Φάκελος</div>
						</div>
						<input type="file" id="files" name="files[]" multiple webkitdirectory>
					</div>
				</form>
				<form enctype="multipart/form-data" action="zipsubmit.php" method="post" id="zip_upload_form">
					<div class="wrapper grayBorderButton">
						<div class="fileUploadButton">
							<img src="resources/images/zipIcon.png">
							<div class="defaultText">ZIP</div>
						</div>
						<input type="file" id="zipFile" name="file" accept=".zip">
					</div>
				</form>
			</div>
		</div>
		<button id="submitUpload" class="orangeGradient sidebarButton">Μεταφόρτωση</button>
		<div id="loadingBar" class="loadingBar">
			<div class="orangeGradient" style="width:0%;display: flex;justify-content: flex-end;">
			</div>
			<span>0%</span>
		</div>
		<div class="editOptions">
			<button id="selectAll" class="sidebarButton orangeGradient"><span class="defaultText">Επιλογή Όλων</span></button>
			<button id="deselectAll" class="sidebarButton orangeGradient"><span class="defaultText">Απαλοιφή επιλογής</span></button>
			<div class="searchSelect">
				<input type="text" id="searchSelect"  placeholder="Αναζήτηση">
				<button id="searchSelectButton" class="normalButton orangeGradient">
					<img  src="resources/images/searchIcon.png">
				</button>
			</div>
		</div>
		<div class="tabButtons">
			<button class="sidebarButton orangeGradient disabledGradient" id="instructionsButton">Οδηγίες</button><button class="sidebarButton orangeGradient currentTab" id="addInstructionButton">Προσθήκη</button>
		</div>
		<div class=tabs>
			<div class="formField" id="formField">
				<form id="imgOptions" class="imgOptions" action="formGenerator.php" method="post" >
					<div class="editInput">
						<span>Διαστάσεις</span>
						<div class='sWrapper'>
							<select id="dimensions" name="dimensions">
								<option value="none" selected disabled>Διάσταση</option>
								<?php
								include("dbConnector.php");
								$dbh=connecttoDB();
								$sql= "select dimensions, aspect_ratio from companyDB.dimension";
								$result=$dbh->query($sql);
								$result=$result->fetchAll();
								foreach ($result as $row){
									echo '<option value='.$row['dimensions'].' data-value='.$row['aspect_ratio'].'>'.$row['dimensions'].'</option>';
								}

								?>
							</select>
						</div>
					</div>
					<div id="optionElements" ></div>
					<div class="editInput">
					<span><span>Ποσότητα:</span><span id="selectedPictures"></span></span>
					<div class="number-input" >
						<input type="number" name="orderAmmount" id="orderAmmount" min="1" value="1" style="min-width:0">
						<div class="spinnerButtons">
							<button onclick="this.parentNode.parentNode.querySelector('input[type=number]').stepUp(); updateAmmounts();"></button>
							<button onclick="this.parentNode.parentNode.querySelector('input[type=number]').stepDown(); updateAmmounts();" ></button>
						</div>
						</div>
					</div>
					<div class="editInput">
						<span>Τρέχων ποσό:</span><div id="currentAmmount"><span style="position:relative; right:0.45em"></span></div>
					</div>
					
					<input type="button" class="normalButton orangeGradient" id="addOrder" name="addOrder" value="Προσθήκη">
				</form>
			</div>
			<div id="instructions"> </div>
		</div>

			<div class="editInput">
				<span class="defaultText">Συνολικό κόστος:</span>
				<div class="defaultText" id="finalTotalCost"></div>
			</div>
				<button id="submitOrder" class="sidebarButton orangeGradient"><span class="defaultText">Υποβολή Παραγγελίας</span></button>
				

	</div>
	<div class="imgfield" id="imgfield">
		<?php
		$count = 0;
		$userDir="uploads/" .getNames();
		$uploadDestination="uploads/" .getNames(). "/temp_order/";
		$previewDestination="uploads/" .getNames(). "/temp_order/previews/";
		$thumbnailDestination="uploads/" .getNames(). "/temp_order/thumbnails/";
			if(is_dir($userDir)){
				foreach(glob($uploadDestination."*.{jpg,png,tiff}",GLOB_BRACE ) as $file){

					$tempDestination=$thumbnailDestination.basename($file);
					$tempDestination2=$previewDestination.basename($file);
					$srcDestination = substr($tempDestination, 0 , (strrpos($tempDestination, ".")));
					$srcDestination2 = substr($tempDestination2, 0 , (strrpos($tempDestination2, ".")));
					echo "<div class=\"thumbnail\" id=\"".basename($file)."\" >
							<div class='imgDiv' style='background-image:url(\"".$srcDestination.".jpg\")' onmouseover='addToTempArray(this.parentNode.id)' onmousedown='addToTempArray(this.parentNode.id)'></div>
						  <div class='defaultText imgName'>".basename($file)."</div>
							</div>";
				}
			}
		?>
	</div>
</div>
</body>
</html>

<script type="text/javascript">
    var tempArray = new Array();
    var mouseDown = 0;
    var arrayOfIds = [];
    var arrayOfCanvas = [];
    var arrayOfCropBox = [];
    var incrementalId=0;
    var editCounter=1;
    var editId;
    var name=<?PHP echo '"'.getNames().'"';?>;

    document.body.onmousedown = function() {
        ++mouseDown;
    };
    document.body.onmouseup = function() {
        mouseDown=0;
    };
    $('img').ondragstart = function() { return false; };


    function calculateCost(){
        var printPaperCost=$("[name='printPaper'] :selected").data("cost");
        var protectionFilmCost=$("[name='protectionFilm'] :selected").data("cost");
        var supportMaterialCost=$("[name='supportMaterials'] :selected").data("cost");
        var sum;
		sum=parseFloat(printPaperCost)+parseFloat(protectionFilmCost)+parseFloat(supportMaterialCost);
        return (($("#imgfield").find('.selected').length)*($("#orderAmmount").val())*sum);
    }

    function updateAmmounts(){
            $("#selectedPictures").html($(".selected").length);
            if(!isNaN(calculateCost().toFixed(2))){
                    $("#currentAmmount span").html(calculateCost().toFixed(2));
            }
    }

    function addToTempArray(thumbId){
        setTimeout(function(){
            if (mouseDown) {
                tempArray.push(thumbId);
                if(document.getElementById(thumbId).classList.contains('selected')){
                    document.getElementById(thumbId).classList.remove("selected");
                    updateAmmounts();
                }
                else{
                    document.getElementById(thumbId).classList.add("selected");
                    updateAmmounts();
                }
            }
        },10);
    }

    function updateTotalCost(){
        var cost=0.0;
        for(i=0; i<arrayOfIds.length;i++){
            if(arrayOfIds[i] != undefined){
                cost+=parseFloat(arrayOfIds[i][0][6].value);
            }
        }
        console.log(cost);
        $("#finalTotalCost").html("<span>"+cost.toFixed(2)+"</span>");
    }

    function capitalize(word) {    
        return $.camelCase("-"+word.replace(/_/g," -")).replace(/-/g,"");
    }
    jQuery(document).ready(function(){
        var selected="none";
        $("#addInstructionButton").click(function(){
            $(this).addClass("currentTab");
            $(this).removeClass("disabledGradient");
            $("#instructionsButton").removeClass("currentTab");
            $("#instructionsButton").addClass("disabledGradient");
            $("#instructions").fadeOut();
            $("#formField").fadeIn();
        });
        $("#instructionsButton").click(function(){
            $(this).addClass("currentTab");
            $(this).removeClass("disabledGradient");
            $("#addInstructionButton").removeClass("currentTab");
            $("#addInstructionButton").addClass("disabledGradient");
            $("#formField").fadeOut();
            $("#instructions").fadeIn();
        });
        $('#selectAll').click(function(){
            $(".thumbnail").each(function(){
                $(this).addClass("selected");
                updateAmmounts();
            })
        });
        $('#deselectAll').click(function(){
            $(".thumbnail").each(function(){
                $(this).removeClass("selected");
                updateAmmounts();
            })
        });

        $('#showUploadMenuButton').click(function(){
			$(".uploadForms").slideToggle();
			$(".uploadForms").css("display","flex");
        });

        $("#searchSelectButton").click(function(){
            var searchString=$("#searchSelect").val();
            $(".thumbnail").each(function(){
                if($(this).attr('id').indexOf(searchString)>=0){
                    $(this).addClass("selected");
                    updateAmmounts();
                }
            })

        });

        $('#upload_form , #zip_upload_form').hover(function(){
            $(this).addClass("highlighted");
            if($(this).attr("id")=="upload_form"){
				$("#zip_upload_form").removeClass("highlighted");
			}
            else if($(this).attr("id")=="zip_upload_form"){
				$("#upload_form").removeClass("highlighted");
            }

        },function(){
            if($(this).attr("id")!=selected){
				$(this).removeClass("highlighted");
			}
				$("#"+selected).addClass("highlighted");
            
        });
        $("#files").change(function(){
            $('#upload_form  .wrapper  .fileUploadButton div').text($(this)[0].files.length+" Files Selected");
            selected="upload_form";
            $("#upload_form").addClass("highlighted");
            $("#zip_upload_form").removeClass("highlighted");
        });
        $("#zipFile").change(function(){
            $('#zip_upload_form  .wrapper  .fileUploadButton div').text($(this).prop("files")[0]['name']);
            selected="zip_upload_form";
            $("#zip_upload_form").addClass("highlighted");
            $("#upload_form").removeClass("highlighted");
        });
        $(window).scroll(function(){
            var scrollHeight=$(window).scrollTop();
            if(scrollHeight<50){
                $(".sidebar").css('top',50-scrollHeight);
            }else{
                $(".sidebar").css('top',0);
            }
        });
        $("#submitUpload").click(function(event){
			if(selected=="upload_form"){
            event.preventDefault();
            $.ajax({
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();

                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);
                            console.log(percentComplete);

                            $("#loadingBar div").css({"width":percentComplete+"%"});
							$("#loadingBar span").html(percentComplete+"%");

                            if (percentComplete === 100) {

                            }

                        }
                    }, false);

                    return xhr;
                },
                url: "submit.php",
                type: "post",
                data: new FormData($("#upload_form")[0]),
                contentType: false,
                processData: false,
                success: function (result) {
                    $("#imgfield").html(result);
                }
            });
			}
			else if(selected=="zip_upload_form"){
				event.preventDefault();
				$.ajax({
					xhr: function() {
						var xhr = new window.XMLHttpRequest();

						xhr.upload.addEventListener("progress", function(evt) {
							if (evt.lengthComputable) {
								var percentComplete = evt.loaded / evt.total;
								percentComplete = parseInt(percentComplete * 100);
								console.log(percentComplete);

								$("#loadingBar div").css({"width":percentComplete+"%"});
								$("#loadingBar span").html(percentComplete+"%");

								if (percentComplete === 100) {

								}

							}
						}, false);

						return xhr;
					},
					url: "zipsubmit.php",
					type: "post",
					data: new FormData($("#zip_upload_form")[0]),
					contentType: false,
					processData: false,
					success: function (result) {
						$("#imgfield").html(result);
					}
            });

				}
		});
        $("#submitOrder").click(function(event){
            $.ajax({
                url:"processPhotos.php",
                type:"post",
                data: {array:arrayOfIds},
                success: function(result){
                    console.log(result);
                }
            });
        });
        $("#imgOptions").change(function(event){
            event.preventDefault();
            var form=$(this);
            $.ajax({
                type: "POST",
                url: "formGenerator.php",
                data: form.serialize(),
                success: function (data){
                    $("#optionElements").html(data);
                    updateAmmounts();
                }
            });
        });
        $("#imgOptions").submit(function(event){
            event.preventDefault();
        });


        $('#addOrder').click(function(event){
            arrayOfIds[incrementalId] = $.map($(".selected"),function(n,i){
                return n.id;
            });
            arrayOfCanvas[incrementalId] = $.map($(".selected"),function(n,i){
                return n.id;
            });
            arrayOfCropBox[incrementalId] = $.map($(".selected"),function(n,i){
                return n.id;
            });
            arrayOfIds[incrementalId].unshift($('#imgOptions').serializeArray());
            if( $("#imgfield").find('.selected').length !==0 && arrayOfIds[incrementalId][0].length >=5) {
                arrayOfIds[incrementalId][0].push({name:"totalCost",value:calculateCost().toString()});
                $('#instructions')
				.append( "<div class=\"orderItem outerDropShadow\" id=" + incrementalId + ">" +
						"<div class='textWrapper'>" +
							"<span class='defaultText'>" + arrayOfIds[incrementalId][0][0].value + " " + capitalize(arrayOfIds[incrementalId][0][2].value) +"</span>" +
							"<span class='defaultText'>"+ capitalize(arrayOfIds[incrementalId][0][4].value) +"</span>" +
							"<span class='defaultText'>Ποσότητα:" + arrayOfIds[incrementalId][0][5].value + "x" + (arrayOfIds[incrementalId].length - 1) + "</span>" +
							"<span class='defaultText'>Κόστος:"+parseFloat(arrayOfIds[incrementalId][0][6].value).toFixed(2)+"</span>" +
                        "</div>" +
                        "<div class='buttonWrapper'>" +
							"<input type='button' class='sidebarButton orangeGradient editButton' value='Επεξεργασία'>" +
							"<input type='button' class='sidebarButton orangeGradient deleteButton' value='Ακύρωση'>" +
                        "</div>" +
                        "</div>" );
                incrementalId++;
                updateTotalCost();
            }
        });
        $(".closeButton").click(function(event){
            $(".imgEditorOverlay").fadeToggle();
            window.cropper.destroy();
            editCounter=1;
        });
        $(document).on("click",".deleteButton",function(event){
            var deleteId=$(this).parent().parent().attr("id");
            arrayOfIds[deleteId]=undefined;
            $(this).parent().parent().remove();
            updateTotalCost();
        });

        $(document).on("click",".editButton",function(event){
            editId=$(this).parent().parent().attr("id");
            if(Array.isArray(arrayOfIds[editId][editCounter])){
                $("#cropImage").attr("src", "/uploads/"+name+"/temp_order/previews/" + arrayOfIds[editId][editCounter][0].substr(0, arrayOfIds[editId][editCounter][0].lastIndexOf(".")) + ".jpg");
            }else {
                $("#cropImage").attr("src", "/uploads/"+name+"/temp_order/previews/" + arrayOfIds[editId][editCounter].substr(0, arrayOfIds[editId][editCounter].lastIndexOf(".")) + ".jpg");
            }
            const image = document.getElementById("cropImage");
            const cropper = new Cropper(image, {
                aspectRatio: $('#dimensions option[value="'+arrayOfIds[editId][0][0]['value']+'"]').data('value'),
                ready: function(event){
                    this.cropper.zoom(-1);
                    this.cropper.setData({"width":image.naturalWidth,});
                    const cropBoxData = this.cropper.getCropBoxData();
                    const containerData = this.cropper.getContainerData();
                    const canvasData=this.cropper.getCanvasData();
                    this.cropper.setCanvasData({"left":canvasData.left, "top":canvasData.top,"width":containerData*$('#dimensions option[value="'+arrayOfIds[editId][0][0]['value']+'"]').data('value'),"height":canvasData.height,});
                    if(Array.isArray(arrayOfIds[editId][editCounter])){
                        this.cropper.setData(arrayOfIds[editId][editCounter][1]);
                        this.cropper.setCanvasData(arrayOfCanvas[editId][editCounter]);
                        this.cropper.setCropBoxData(arrayOfCropBox[editId][editCounter]);
                    }else if(canvasData.naturalHeight>canvasData.naturalWidth){
                        var aspect=1/$('#dimensions option[value="'+arrayOfIds[editId][0][0]['value']+'"]').data('value');
                        this.cropper.setAspectRatio(aspect);
                        this.cropper.setCropBoxData({"left":(containerData.width - canvasData.height*aspect)/2,"top":(containerData.height - canvasData.height)/2,"width":canvasData.height*aspect,"height":canvasData.height});
                    }else{
                        var aspect=$('#dimensions option[value="'+arrayOfIds[editId][0][0]['value']+'"]').data('value');
                        this.cropper.setAspectRatio(aspect);
                        this.cropper.setCropBoxData({"left":(containerData.width-canvasData.width)/2,"top":(containerData.height-canvasData.width*(1/aspect))/2,"width":canvasData.width,"height":canvasData.width*aspect});
                    }
                    $('#originalResolutionx').html(canvasData.naturalWidth);
                    $('#originalResolutiony').html(canvasData.naturalHeight);
                },
                crop: function (event) {
                    $('#cropWidth').html(Math.round(event.detail.width));
                    $('#cropHeight').html(Math.round(event.detail.height));
                    $('#topLeftx').html(Math.round(event.detail.x));
                    $('#topLefty').html(Math.round(event.detail.y));
                },
            });
            console.log("hello");
            $(".imgEditorOverlay").fadeToggle();
            window.cropper=cropper;
            window.image=image;
            window.cropper.zoomTo(1);
        });
        $(".prevButton").click(function(event){
            if(editCounter==1){}
            else{
                editCounter--;
                if(Array.isArray(arrayOfIds[editId][editCounter])){
                    window.cropper.replace("/uploads/"+name+"/temp_order/previews/"+arrayOfIds[editId][editCounter][0].substr(0,arrayOfIds[editId][editCounter][0].lastIndexOf("."))+".jpg");
                    console.log("array");

                }else{
                    window.cropper.replace("/uploads/"+name+"/temp_order/previews/"+arrayOfIds[editId][editCounter].substr(0,arrayOfIds[editId][editCounter].lastIndexOf("."))+".jpg");
                    console.log("not array");
                }
            }
        });
        $(".nextButton").click(function(event){
            if(editCounter==arrayOfIds[editId].length-1){}
            else{
                editCounter++;
                if(Array.isArray(arrayOfIds[editId][editCounter])){
                    window.cropper.replace("/uploads/"+name+"/temp_order/previews/"+arrayOfIds[editId][editCounter][0].substr(0,arrayOfIds[editId][editCounter][0].lastIndexOf("."))+".jpg");

                }else{
                    window.cropper.replace("/uploads/"+name+"/temp_order/previews/"+arrayOfIds[editId][editCounter].substr(0,arrayOfIds[editId][editCounter].lastIndexOf("."))+".jpg");
                }
            }
        });
        $(".rotateLButton").click(function(event){
            window.cropper.rotate(-90);1
        });
        $(".rotateRButton").click(function(event){
            window.cropper.rotate(90);1
        });
        $(".wideAspectRatio").click(function(){
            window.cropper.setAspectRatio($('#dimensions option[value="'+arrayOfIds[editId][0][0]['value']+'"]').data('value'));
            window.cropper.setData({"width":window.cropper.getImageData().naturalWidth,});
            const cropBoxData = window.cropper.getCropBoxData();
            const containerData = window.cropper.getContainerData();
            window.cropper.setCropBoxData({"left":(containerData.width-cropBoxData.width)/2,"top":(containerData.height-cropBoxData.height)/2,});
        });
        $(".tallAspectRatio").click(function(){
            window.cropper.setAspectRatio(1 / $('#dimensions option[value="'+arrayOfIds[editId][0][0]['value']+'"]').data('value'));
            window.cropper.setData({"width":window.cropper.getImageData().naturalWidth,});
            const cropBoxData = window.cropper.getCropBoxData();
            const containerData = window.cropper.getContainerData();
            window.cropper.setCropBoxData({"left":(containerData.width-cropBoxData.width)/2,"top":(containerData.height-cropBoxData.height)/2,});
        });
        $(".fitWidthButton").click(function(){
            var tcanvasData=window.cropper.getCanvasData();
            var aspect=window.cropper.getCropBoxData().width/window.cropper.getCropBoxData().height;
            window.cropper.setCropBoxData({"left":tcanvasData.left,"top":tcanvasData.top-(tcanvasData.width*(1/aspect)-tcanvasData.height)/2,"width":tcanvasData.width,"height":tcanvasData.width*(1/aspect)});
        });
        $(".fitHeightButton").click(function(){
            var tcanvasData=window.cropper.getCanvasData();
            var aspect=window.cropper.getCropBoxData().width/window.cropper.getCropBoxData().height;
            console.log(aspect);
            console.log(tcanvasData);
            window.cropper.setCropBoxData({"left":tcanvasData.left-(tcanvasData.height*aspect-tcanvasData.width)/2,"top":tcanvasData.top,"width":tcanvasData.height*aspect,"height":tcanvasData.height});

        });
        $(".applyNextButton").click(function(event){
            if(Array.isArray(arrayOfIds[editId][editCounter])){
                arrayOfIds[editId][editCounter][1]=window.cropper.getData();
                arrayOfCanvas[editId][editCounter]=window.cropper.getCanvasData();
                arrayOfCropBox[editId][editCounter]=window.cropper.getCropBoxData();
            }else{
                arrayOfIds[editId][editCounter]=[arrayOfIds[editId][editCounter],window.cropper.getData()];
                arrayOfCanvas[editId][editCounter]=window.cropper.getCanvasData();
                arrayOfCropBox[editId][editCounter]=window.cropper.getCropBoxData();
            }
            if(editCounter==arrayOfIds[editId].length-1){}
            else{
                editCounter++;
                if(Array.isArray(arrayOfIds[editId][editCounter])){
                    window.cropper.replace("/uploads/"+name+"/temp_order/previews/"+arrayOfIds[editId][editCounter][0].substr(0,arrayOfIds[editId][editCounter][0].lastIndexOf("."))+".jpg");

                }else{
                    window.cropper.replace("/uploads/"+name+"/temp_order/previews/"+arrayOfIds[editId][editCounter].substr(0,arrayOfIds[editId][editCounter].lastIndexOf("."))+".jpg");
                }
            }
        });
        $(".applyPrevButton").click(function(event){
            if(Array.isArray(arrayOfIds[editId][editCounter])){
                arrayOfIds[editId][editCounter][1]=window.cropper.getData();
                arrayOfCanvas[editId][editCounter]=window.cropper.getCanvasData();
                arrayOfCropBox[editId][editCounter]=window.cropper.getCropBoxData();
            }else{
                arrayOfIds[editId][editCounter]=[arrayOfIds[editId][editCounter],window.cropper.getData()];
                arrayOfCanvas[editId][editCounter]=window.cropper.getCanvasData();
                arrayOfCropBox[editId][editCounter]=window.cropper.getCropBoxData();
            }

            if(editCounter==1){}
            else{
                editCounter--;
                if(Array.isArray(arrayOfIds[editId][editCounter])){
                    window.cropper.replace("/uploads/"+name+"/temp_order/previews/"+arrayOfIds[editId][editCounter][0].substr(0,arrayOfIds[editId][editCounter][0].lastIndexOf("."))+".jpg");

                }else{
                    window.cropper.replace("/uploads/"+name+"/temp_order/previews/"+arrayOfIds[editId][editCounter].substr(0,arrayOfIds[editId][editCounter].lastIndexOf("."))+".jpg");
                }
            }
        });
    });
</script>
<?php

?>


