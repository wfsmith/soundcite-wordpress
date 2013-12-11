(function() {

    /* SoundCite */
    tinymce.create('tinymce.plugins.soundcite', {
        init : function(ed, url) {

            ed.addCommand('cmd_soundcite', function() {

                ed.windowManager.open({
                    file : url + '/../dialog.php',
                    width : 340,
                    height : 220,
                    inline : 1
                }, {
                    plugin_url : url
                });
            });

            ed.addButton('soundcite', {
                title : 'SoundCite',
                image : url + '/../images/soundcite.png',
                cmd : 'cmd_soundcite'

            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname : 'SoundCite shortcode',
                author : 'WBUR'
            };
        }

    });

    tinymce.PluginManager.add('soundcite', tinymce.plugins.soundcite);

})();