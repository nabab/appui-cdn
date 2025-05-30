<bbn-table ref="cdn_management"
           :source="sourceTable"
           class="bbn-w-100"
           :info="true"
           :sortable="true"
           :editable="true"
           :pageable="true"
           editor="appui-cdn-management-library_edit"
           toolbar="appui-cdn-management-toolbar"
           expander="appui-cdn-management-versions"
           :order="[{field: 'title', dir: 'ASC'}]">
  <bbns-column label="<?= _("Title") ?>"
               field="title"
               :min-width="180"
               :sortable="false"/>

  <bbns-column label="<?= _("Folder name") ?>"
               :min-width="150"
               field="name"/>

  <bbns-column label="<?= _("Function Name") ?>"
               :min-width="150"
               field="fname"/>

  <bbns-column label="<?= _("Latest") ?>"
               field="latest"
               cls="bbn-c"
               :width="80"/>

  <bbns-column label="<?= _('Infos') ?>"
               :render="showInfos"
               cls="bbn-c"
               :width="210"/>

  <bbns-column cls="bbn-c buttonTable"
               :buttons="buttons"
               :width="130"/>
</bbn-table>
