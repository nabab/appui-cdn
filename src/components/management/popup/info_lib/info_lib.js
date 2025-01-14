(() => {
  return {
    data(){
      return {
        infos:[
          {text: bbn._('Title'), value: this.source.info.title ? this.source.info.title : '--' },
          {text: bbn._('Folder name'), value: this.source.info.name ? this.source.info.name : '--' },
          {text: bbn._('Function name'), value: this.source.info.fname ? this.source.info.fname : '--' },
          {text: bbn._('Latest version'), value: this.source.info.latest ? this.source.info.latest : '--' },
          {text: bbn._('Author'), value: this.source.info.author ? this.source.info.author : '--' },
          {text: bbn._('Description'), value: this.source.info.description ? this.source.info.description : '--' },
          {text: bbn._('Licence'), value: this.source.info.licence ? this.source.info.licence : '--' },
          {text: bbn._('Website'), value: this.source.info.website ? this.source.info.website : '--' },
          {text: bbn._('Download'), value: this.source.info.download_link ? this.source.info.download_link : '--' },
          {text: bbn._('Documentation'), value: this.source.info.doc_link ? this.source.info.doc_link : '--' },
          {text: bbn._('Support'), value: this.source.info.git ? this.source.info.git : '--' },
          {text: 'Github', value: this.source.info.support_link ? this.source.info.support_link : '--' },
        ],
        versions: this.source.versions
      }
    },
    computed: {
      colsInfo(){
        if ( this.source.info ){
          return [
            {
              label: ' ',
              field: 'text',
              width: "180"
            },
            {
              label: ' ',
              field: 'value',
              width: "300"
            }
          ];
        }
        return [];
      },
    }
  }
})();
