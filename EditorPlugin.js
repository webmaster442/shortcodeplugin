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
                        text: 'Archívum',
                        onclick: function () {
                            ed.windowManager.open({
                                title: 'Archívum létrehozása',
                                body: [{ type: 'listbox', name: 'type', label: 'Típus :', onselect: function (e) { }, 'values': [{ text: 'Éves', value: 'year' }, { text: 'Kategória', value: 'category' }] },
                                { type: 'textbox', name: 'exclude', label: 'Kihagyott kategóriák: ', value: '' }],
                                onsubmit: function (e) {
                                    ed.focus();
                                    var t = '[archive type="' + e.data.type + '" exclude="' + e.data.exclude + '"]';
                                    ed.insertContent(t);
                                }
                            })
                        }
                    },
                    {
                        text: 'Cimkefelhő',
                        onclick: function () {
                            ed.windowManager.open({
                                title: 'Cimkefelhő létrehozása',
                                body: [{ type: 'textbox', name: 'smallest', label: 'Legkisebb méret: ', value: '8' },
                                       { type: 'textbox', name: 'largest', label: 'Legnagyobb méret: ', value: '22' },
                                       { type: 'listbox', name: 'unit', label: 'Mértékegység :', onselect: function (e) { }, 'values': [{ text: 'pt', value: 'pt' }, { text: 'em', value: 'em' }, { text: '%', value: '%' }] },
                                       { type: 'textbox', name: 'number', label: 'Cimkék száma: ', value: '45' },
                                       { type: 'listbox', name: 'format', label: 'Formátum :', onselect: function (e) { }, 'values': [{ text: 'Flat', value: 'flat' }, { text: 'list', value: 'list' }] },
                                       { type: 'listbox', name: 'orderby', label: 'Rendezés :', onselect: function (e) { }, 'values': [{ text: 'Név', value: 'name' }, { text: 'Darab', value: 'count' }] },
                                       { type: 'listbox', name: 'order', label: 'Rendezési szempont :', onselect: function (e) { }, 'values': [{ text: 'Növekvő', value: 'ASC' }, { text: 'Csökkenő', value: 'DESC' }, { text: 'Véletlen', value: 'RAND' }] } ],
                                onsubmit: function (e) {
                                    ed.focus();
                                    var t = '[tagcloud smallest="' + e.data.smallest + '" largest="' + e.data.largest + '" unit="' + e.data.unit + '" number="' + e.data.number + '" format="' + e.data.format + '" orderby="' + e.data.orderby + '" order="' + e.data.order + '"   /]';
                                    ed.insertContent(t);
                                }
                            })
                        }
                    },
                    {
                        text: 'Tartalom Bejelentkezett felhasználóknak',
                        onclick: function () {
                            var selected_text = ed.selection.getContent();
                            if (selected_text.length < 1) {
                                alert("Jelölj ki egy szövegrészletet, amit csak bejelentkezett felhasználók számára szeretnél láthatóvá tenni.");
                            }
                            else {
                                var wrapped = '[logedin]' + selected_text + '[/logedin]';
                                ed.insertContent(wrapped);
                            }
                        }
                    },
                    {
                        text: 'Tartalom NEM Bejelentkezett felhasználóknak',
                        onclick: function () {
                            var selected_text = ed.selection.getContent();
                            if (selected_text.length < 1) {
                                alert("Jelölj ki egy szövegrészletet, amit csak nem bejelentkezett felhasználók számára szeretnél láthatóvá tenni.");
                            }
                            else {
                                var wrapped = '[notlogedin]' + selected_text + '[/notlogedin]';
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
                    }
                ]
            });
            ed.addButton('button_codes2', {
                type: 'splitbutton',
                title: 'További Kódok',
                image: url + '/code-brackets.png',
                onclick: function () { alert("Válassz a legördülő menüből beillesztendő kódot"); },
                menu: [
                    {
                        text: 'E-mail cím védelme',
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
                                body: [{ type: 'textbox', name: 'id', label: 'Mappa ID' },
                                { type: 'textbox', name: 'height', label: 'Magasság', value: '600px' },],
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
                                { type: 'textbox', name: 'height', label: 'Magasság', value: '600px' },],
                                onsubmit: function (e) {
                                    ed.focus();
                                    var t = '[drivefolder-grid id="' + e.data.id + '" height="' + e.data.height + '"]';
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
                                    body: [{ type: 'textbox', name: 'divider', label: 'Elválasztó:', value: ';' },
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
                                    body: [{ type: 'textbox', name: 'divider', label: 'Elválasztó:', value: ';' }],
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
                                       { type: 'textbox', name: 'progress', label: '%: ', value: '100' }],
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
                                    width: 500,
                                    height: 350,
                                    title: 'Lábjegyzet beillesztése',
                                    body: [{ type: 'textbox', multiline: true, name: 'footnote', label: 'Lábjegyzet', style: 'height: 250px' }],
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
