(function () {
    /* Register the buttons */
    tinymce.create('tinymce.plugins.MyButtons', {
        init: function (ed, url) {
            /**
            * Inserts shortcode content
            */
            ed.addButton('button_codes', {
                type: 'splitbutton',
                title: 'Kódok',
                image: url + '/code-brackets.png',
                onclick: function () { alert("Válassz a legördülő menüből beillesztendő kódot"); },
                menu: [
                    {
                        text: 'Aloldalak liszázása',
                        onclick: function () {
                            ed.insertContent('[subpages]');
                        }
                    },
                    {
                        text: 'E-mail',
                        onclick: function () {
                            var selected_text = ed.selection.getContent();
                            if (selected_text.length < 1) {
                                alert("Először jelölj ki egy szöveget, amit átalakítani szeretnél");
                                return;
                            }
                            var wrapped = '[email]' + selected_text + '[/email]';
                            ed.insertContent(wrapped);
                        }
                    },
                    {
                        text: 'Google Drive Mappa - Lista',
                        onclick: function () {
                            ed.windowManager.open({
                                title: 'Google Drive Ikon nézet',
                                body: [{ type: 'textbox', name: 'id:', label: 'Mappa ID' },
                                       { type: 'textbox', name: 'height', label: 'Magasság', text: '600px' }, ],
                                onsubmit: function (e) {
                                    ed.focus();
                                    var t = '[drivefolder-list id="' + e.data.id + '" height="' + e.data.height + '"]';
                                    ed.insertContent(t);
                                }
                            })
                        }
                    },
                    {
                        text: 'Google Drive Mappa - Ikonok',
                        onclick: function () {
                            ed.windowManager.open({
                                title: 'Google Drive Ikon nézet',
                                body: [{ type: 'textbox', name: 'id:', label: 'Mappa ID' },
                                       { type: 'textbox', name: 'height', label: 'Magasság', text: '600px' }, ],
                                onsubmit: function (e) {
                                    ed.focus();
                                    var t = '[drivefolder-grid id="' + e.data.id + '" height="' + e.data.height + '"]';
                                    ed.insertContent(t);
                                }
                            })
                        }
                    },
                    {
                        text: 'Archívum',
                        onclick: function () {
                            ed.insertContent('[archive]');
                        }
                    },
                    {
                        text: 'Markdown',
                        onclick: function () {
                            var selected_text = ed.selection.getContent();
                            if (selected_text.length < 1) {
                                alert("Először jelölj ki egy szöveget, amit átalakítani szeretnél");
                                return;
                            }
                            var wrapped = '[markdown]' + selected_text + '[/markdown]';
                            ed.insertContent(wrapped);
                        }
                    },
                    {
                        text: 'Forráskód',
                        onclick: function () {
                            ed.windowManager.open({
                                title: 'Forráskód beillesztése',
								width: 500,
								height: 400,
								body: [{ type: 'textbox', name: 'language', label: 'Nyelv' },
                                       { type: 'checkbox', name: 'linenumbers', text: 'Sorok számozása', checked: true },
                                       { type: 'textbox', multiline: true, name: 'source', label: 'Forrráskód', style: 'height: 280px' }],
                                onsubmit: function (e) {
                                    ed.focus();
                                    ed.selection.setContent('<pre class="language-' + e.data.language + '"><code>' + e.data.source + '</code></pre>');
                                }
                            })
                        }
                    }
                ]
            });
        },
        createControl: function (n, cm) {
            return null;
        },
    });
    /* Start the buttons */
    tinymce.PluginManager.add('my_button_script', tinymce.plugins.MyButtons);
})();
