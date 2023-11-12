<bbn-form :action="root + 'action/library'"
          :source="source.row"
          :data="complementaryData"
          ref="form_library"
          @failure="failure"
          @success="success"
          confirm-leave="<?=_("Are you sure you want to close?")?>"
          :buttons="currentButton"
          :scrollable="true"
          v-if="showForm">
 <div class="bbn-lpadded bbn-grid-fields" v-if="!configuratorLibrary">
   <label>GitHub</label>
   <div class="bbn-flex-width">
     <bbn-input class="bbn-flex-fill" v-model="source.row.git"/>
     <bbn-button @click="getInfo"
                 icon="nf nf-fa-download"
                 title="<?=_("Import library's info from GitHub")?>"
                 v-if="source.row.git"/>
   </div>

    <label class="bbn-r">
      <?=_("Title")?>
    </label>
    <bbn-input name="title" v-model="source.row.title"/>

    <label class="bbn-r">
      <?=_("Folder name")?>
    </label>
    <bbn-input v-if="management.action.editLib" v-model="newName"/>
    <bbn-input v-else v-model="source.row.name"/>
    <label class="bbn-r">
      <?=_("Function name")?>
    </label>
    <bbn-input v-model="source.row.fname"/>

    <label class="bbn-r">
      <?=_("Description")?>
    </label>
    <bbn-input v-model="source.row.description"/>

    <label class="bbn-r">
      <?=_("Author")?>
    </label>
    <bbn-input v-model="source.row.author"/>

    <label class="bbn-r">
      <?=_("Licence")?>
    </label>
    <div>
      <bbn-dropdown style="width:200px"
                    ref="listLicences"
                    :source="licencesList"
                    v-model="source.row.licence"/>
      <bbn-input v-model="source.row.licence"/>
    </div>

    <label>
      <?=_("Website")?>
    </label>
    <bbn-input v-model="source.row.website"/>

    <label class="bbn-r">
      <?=_("Download link")?>
    </label>
    <bbn-input v-model="source.row.download_link"/>

    <label class="bbn-r">
      <?=_("Documentation")?>
    </label>
    <bbn-input v-model="source.row.doc_link"/>

    <label class="bbn-r">
      <?=_("Support")?>
    </label>
    <bbn-input v-model="source.row.support_link"/>
    <label v-if="source.row.versions"><?=_("Version")?></label>
    <bbn-dropdown v-if="source.row.versions"
                  style="width: 50%"
                  v-model="source.row.git_id"
                  :source="source.row.versions"
                  ref="listVersions"/>
  </div>
</bbn-form>
