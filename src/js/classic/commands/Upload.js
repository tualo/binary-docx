Ext.define('Tualo.fibuconv.commands.Upload', {
    statics: {
      glyph: 'upload',
      title: 'Datei hochladen',
      tooltip: 'Datei hochladen',
    },
    requires: [
        'Tualo.fibuconv.commands.models.Upload',
        'Tualo.fibuconv.commands.controller.Upload'
      ],
    controller: 'fibuconv_upload_command',
    viewModel: {
        type: 'fibuconv_upload_command'
    },
    extend: 'Ext.panel.Panel',
    alias: 'widget.fibuconv_upload_command',
    layout: 'fit',
    items: [
        {
            multiSelect: true,
            xtype: 'grid',
            reference: 'uploadgrid',
            columns: [{
                header: 'Name',
                dataIndex: 'name',
                flex: 2
            }, {
                header: 'Size',
                dataIndex: 'size',
                flex: 1,
                renderer: Ext.util.Format.fileSize
            }, {
                header: 'Status',
                dataIndex: 'status',
                flex: 1,
                renderer: this.rendererStatus
            }],

            viewConfig: {
                emptyText: 'Bitte die Dateien hier hinein ziehen',
                deferEmptyText: false
            },

            bind: {
                store: '{files}'
            },

            listeners: {

                drop: {
                    element: 'el',
                    fn: 'onDrop'
                },

                dragstart: {
                    element: 'el',
                    fn: 'addDropZone'
                },

                dragenter: {
                    element: 'el',
                    fn: 'addDropZone'
                },

                dragover: {
                    element: 'el',
                    fn: 'addDropZone'
                },

                dragleave: {
                    element: 'el',
                    fn: 'removeDropZone'
                },

                dragexit: {
                    element: 'el',
                    fn: 'removeDropZone'
                },

            },

            

           

            tbar: [{
                text: "Hochladen",
                handler: 'onUploadClicked'
            }, {
                text: "Auswahl löschen",
                handler: 'onEraseSelected' 
            }]


        }
    ],
    loadRecord: function (record, records, selectedrecords, view) {
      this.record = record;
      this.records = records;
      this.selectedrecords = selectedrecords;
      this.view = view;
      console.log('loadRecord', arguments);
    },
    getNextText: function () {
      return 'Anlegen';
    },
    run: async function () {
      let parent = Ext.getCmp(this.calleeId);
      var newrecord = parent.getController().cloneRecord();
      newrecord.set('kostenstelle', -1);
      var store = Ext.create(Ext.ClassManager.getName(Ext.ClassManager.getByAlias('store.' + newrecord.get('__table_name') + '_store')), {
        autoLoad: false,
        autoSync: false,
        pageSize: 100000,
        type: newrecord.get('__table_name') + '_store'
      });
      store.setFilters({
        property:  'kundennummer',
        operator: 'eq',
        value: 'kundennummer'
      });
      store.load({
        callback: function () {
          var r = store.getRange();
          var m = 0;
          r.forEach(function (element) {
            m = Math.max(m, element.get( 'kostenstelle'));
          });
          m++;
          newrecord.set( 'kostenstelle', m);
        }
      });
      return null;
    }
  });
  