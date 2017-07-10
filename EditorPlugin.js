/*
ShortCode Plugin Wordpress Editor plugin
Írta: Ruzsinszki Gábor
*/
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
                            ed.windowManager.open({
                                title: 'Aloldalak listázása',
                                body: [{ type: 'textbox', name: 'wrap', label: 'HTML beágyazási elem:' }],
                                onsubmit: function (e) {
                                    ed.focus();
                                    var t = '[subpages wrap="' + e.data.wrap + '"]';
                                    ed.insertContent(t);
                                }
                            })
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
                        text: 'Bejelentkezett tartalom',
                        onclick: function () {
                            var selected_text = ed.selection.getContent();
                            if (selected_text.length < 1) {
                                alert("Először jelölj ki egy szöveget, ami csak a bejelentkezett felhasználóknak lesz látható");
                                return;
                            }
                            var wrapped = '[logedin]' + selected_text + '[/logedin]';
                            ed.insertContent(wrapped);
                        }
                    },
                    {
                        text: 'Google Drive Mappa - Lista',
                        onclick: function () {
                            ed.windowManager.open({
                                title: 'Google Drive Ikon nézet',
                                body: [{ type: 'textbox', name: 'id', label: 'Mappa ID' },
                                { type: 'textbox', name: 'height', label: 'Magasság', text: '600px' },],
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
                                body: [{ type: 'textbox', name: 'id', label: 'Mappa ID' },
                                { type: 'textbox', name: 'height', label: 'Magasság', text: '600px' },],
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
                            ed.windowManager.open({
                                title: 'Archívum létrehozása',
                                body: [{ type: 'listbox', name: 'type', label: 'Típus :', onselect: function (e) { }, 'values': [{ text: 'Éves', value: 'year' }, { text: 'Kategória', value: 'category' }] },
                                { type: 'textbox', name: 'exclude', label: 'Kihagyott kategóriák: ', text: '' }],
                                onsubmit: function (e) {
                                    ed.focus();
                                    var t = '[archive type="' + e.data.type + '" exclude="' + e.data.exclude + '"]';
                                    ed.insertContent(t);
                                }
                            })
                        }
                    },
                    {
                        text: 'Markdown',
                        onclick: function () {
                            var selected_text = ed.selection.getContent();
                            if (selected_text.length < 1) {
                                ed.windowManager.open({
                                    title: 'Markdown beillesztése',
                                    width: 500,
                                    height: 400,
                                    body: [{ type: 'textbox', multiline: true, name: 'source', label: 'Markdown szöveg', style: 'height: 250px' }],
                                    onsubmit: function (e) {
                                        ed.focus()
                                        var md = e.data.source;
                                        ed.selection.setContent('[markdown]' + md + '[/markdown]');
                                    }
                                })
                            }
                            else {
                                var wrapped = '[markdown]' + selected_text + '[/markdown]';
                                ed.insertContent(wrapped);
                            }
                        }
                    },
                    {
                        text: 'Be/Kijelentkezési link',
                        onclick: function () {
                            ed.insertContent('[loginlogout]');
                        }
                    },
                    {
                        text: 'Regisztrációs link',
                        onclick: function () {
                            ed.insertContent('[registerlink]');
                        }
                    },
                    {
                        text: 'Forráskód',
                        onclick: function () {
                            var selected_text = ed.selection.getContent();
                            if (selected_text.length > 0) {
                                ed.windowManager.open({
                                    title: 'Forráskód beillesztése',
                                    body: [{ type: 'textbox', name: 'language', label: 'Nyelv' },
                                    { type: 'checkbox', name: 'linenumbers', text: 'Sorok számozása', checked: false },
                                    { type: 'checkbox', name: 'needescape', text: 'Speciális karakterek konvertálása', checked: false }],
                                    onsubmit: function (e) {
                                        ed.focus()
                                        var sourcecode = selected_text;
                                        if (e.data.needescape) {
                                            sourcecode = sourcecode.replace(/&/g, "&amp;").replace(/>/g, "&gt;").replace(/</g, "&lt;").replace(/"/g, "&quot;");
                                        }
                                        var css = 'language-' + e.data.language;
                                        if (e.data.linenumbers)
                                            css += " line-numbers";
                                        ed.selection.setContent('<pre class="' + css + '"><code>' + sourcecode + '</code></pre>');
                                    }
                                })
                            }
                            else {
                                ed.windowManager.open({
                                    title: 'Forráskód beillesztése',
                                    width: 500,
                                    height: 400,
                                    body: [{ type: 'textbox', name: 'language', label: 'Nyelv' },
                                    { type: 'checkbox', name: 'linenumbers', text: 'Sorok számozása', checked: true },
                                    { type: 'checkbox', name: 'needescape', text: 'Speciális karakterek konvertálása', checked: true },
                                    { type: 'textbox', multiline: true, name: 'source', label: 'Forrráskód', style: 'height: 250px' }],
                                    onsubmit: function (e) {
                                        ed.focus()
                                        var sourcecode = e.data.source;
                                        if (e.data.needescape) {
                                            sourcecode = sourcecode.replace(/&/g, "&amp;").replace(/>/g, "&gt;").replace(/</g, "&lt;").replace(/"/g, "&quot;");
                                        }
                                        var css = 'language-' + e.data.language;
                                        if (e.data.linenumbers)
                                            css += " line-numbers";
                                        ed.selection.setContent('<pre class="' + css + '"><code>' + sourcecode + '</code></pre>');
                                    }
                                })
                            }
                        }
                    },
                    {
                        text: 'CSV Táblázat',
                        onclick: function () {
                            var selected_text = ed.selection.getContent();
                            if (selected_text.length <= 0) {
                                ed.windowManager.open({
                                    title: 'CSV táblázat',
                                    width: 500,
                                    height: 400,
                                    body: [{ type: 'textbox', name: 'divider', label: 'Elválasztó:', text: ';' },
                                    { type: 'textbox', multiline: true, name: 'csv', label: 'csv adat:', style: 'height: 250px' }],
                                    onsubmit: function (e) {
                                        ed.focus();
                                        var csv = '[csvtable delimiter="' + e.data.divider + '"]' + ed.data.csv + '[/csvtable]';

                                        ed.insertContent(csv);
                                    }
                                })
                            }
                            else {
                                ed.windowManager.open({
                                    title: 'CSV táblázat',
                                    body: [{ type: 'textbox', name: 'divider', label: 'Elválasztó:', text: ';' }],
                                    onsubmit: function (e) {
                                        ed.focus();
                                        var csv = '[csvtable delimiter="' + e.data.divider + '"]' + selected_text + '[/csvtable]';
                                        ed.insertContent(csv);
                                    }
                                })
                            }
                        }
                    },
                    {
                        text: 'Folyamatjelző',
                        onclick: function () {
                            ed.windowManager.open({
                                title: 'Folyamatjelző beszúrása',
                                body: [{ type: 'listbox', name: 'size', label: 'Méret :', onselect: function (e) { }, 'values': [{ text: 'Kicsi', value: 'small' }, { text: 'Normál', value: 'normal' }, { text: 'Nagy', value: 'big' }] },
                                       { type: 'listbox', name: 'color', label: 'Szín :', onselect: function (e) { }, 'values': [{ text: 'Kék', value: 'blue' }, { text: 'Zöld', value: 'green' }, { text: 'Narancs', value: 'orange' }] },
                                       { type: 'textbox', name: 'progress', label: '%: ', text: '100' }],
                                onsubmit: function (e) {
                                    ed.focus();
                                    var pt = '[circleprogress progress="' + e.data.progress + '" size="' + e.data.size + '" color="' + e.data.color + '"/]';
                                    ed.insertContent(pt);
                                }
                            })
                        }
                    },
                    {
                        text: 'Lábjegyzet',
                        onclick: function () {
                            var selected_text = ed.selection.getContent();
                            if (selected_text.length > 0) {
                                var wrapped = '[note]' + selected_text + '[/note]';
                                ed.insertContent(wrapped);
                            }
                            else {
                                ed.windowManager.open({
                                    title: 'Lábjegyzet beillesztése',
                                    body: [{ type: 'textbox', name: 'footnote', label: 'Lábjegyzet' }],
                                    onsubmit: function (e) {
                                        ed.focus()
                                        var wrapped = '[note]' + e.data.footnote + '[/note]';
                                        ed.selection.setContent(wrapped);
                                    }
                                })
                            }
                        }
                    },
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
