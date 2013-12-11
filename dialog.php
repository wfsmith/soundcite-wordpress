<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <script type='text/javascript' src='/wp-includes/js/jquery/jquery.js?ver=1.10.2'></script>
    <script type="text/javascript" src="/wp-includes/js/tinymce/tiny_mce_popup.js?ver=3392"></script>
    <style type="text/css">
        BODY { padding:10px; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#333; }

        .sc-sect-title { margin:0 0 10px 0; border-bottom:1px solid #ffffff; padding:0 0 5px 0; font-weight:bold;  }
        .sc-sect-title span { font-weight:normal; }

        .sc-time-input { float:left; margin:0 15px 0 0; }
        .sc-time-input input { width:60px; }
        .sc-time-input label { color:#0F9BD1; }

        #sc-url { width:80%; }
        #sc-urlresolve, #sc-times { margin:0 0 25px 0; }

        .sc-action { background-color:#f5f5f5; }
        .sc-action-btn { float:left; }
        .sc-right { float:right; }
        .sc-right input { width:140px !important; } /* wp core overrides */

        .clearfix:before, .clearfix:after { content: ""; display: table; }
        .clearfix:after { clear: both; }
        .clearfix { *zoom: 1; }
        * :focus { outline: 0; }

    </style>
    <title>SoundCite Embed</title>
</head>

<body>

    <form name="source" onSubmit="return soundcite.insert();" action="#">
        <input type="hidden" name="sc-id" id="sc-id" />

        <div class="sc-sect-title">Resolve Soundcloud URL</div>
        <div id="sc-urlresolve">
            <input type="text" name="sc-url" id="sc-url" value="" class="mceFocus" />
            <input type="button" name="sc-resolve" id="sc-resolve" value="Get" />
        </div>

        <div class="sc-sect-title">Sound Clip Times <span>(mm:ss or hh:mm:ss)</span></div>
        <div id="sc-times">
            <div class="sc-time-input"><label for="sc-start">start time: </label> <input type="text" id="sc-start" name="sc-start" value="00:00" /></div>
            <div class="sc-time-input"><label for="sc-end">end time: </label> <input type="text" id="sc-end" name="sc-end" value="00:00" /></div>
            <div style="clear:both;"></div>
        </div>

        <div class="sc-action clearfix">
            <div class="sc-action-btn">
                <input type="button" name="cancel" value="Cancel" onClick="tinyMCEPopup.close();" id="cancel" />
            </div>

            <div class="sc-action-btn sc-right">
                <input type="submit" name="insert" value="Insert SoundCite" id="insert" />
            </div>
        </div>
    </form>

    <script type="text/javascript">

        function scResolver(url) {

            var request = jQuery.ajax({
                url:  "<?php echo substr(dirname(__FILE__), strlen($_SERVER['DOCUMENT_ROOT'])) ?>/resolver.php?url=" + url,
                type: "GET",
                dataType: "json"
            });

            request.done(function( data ) {
                if(data) {
                    jQuery('#sc-id').val(data.id);
                    jQuery('#sc-urlresolve').fadeOut(function(){
                        jQuery('#sc-urlresolve').html("Track found: " + data.id).fadeIn();
                    });
                }
            });

            request.fail(function( jqXHR, textStatus, errorThrown ) {
                // error
            });

        }

        var soundcite = {

            insert : function() {

                var start = document.getElementById('sc-start').value;
                var end = document.getElementById('sc-end').value;
                var id = document.getElementById('sc-id').value;

                var selectedText = tinyMCEPopup.editor.selection.getContent( {format : "text"} );
                var output = '[soundcite id="'+id+'" start="'+start+'" end="'+end+'"]' + selectedText + '[/soundcite] ';

                tinyMCEPopup.editor.execCommand('mceInsertContent', false, output);
                tinyMCEPopup.close();
            }
        };

        /* Get the soundcloud track ID from the url passed, set sc-id hidden filed value to the soundcloud track ID */
        jQuery('#sc-resolve').click(function(){
            var url = jQuery('#sc-url').val();
            scResolver(url);
        });

    </script>
</body>
</html>