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
           :order="[{field: 'title', dir: 'ASC'}]"
>
  <bbns-column title="<?= _("Title") ?>"
               field="title"
               :sortable="false"
  ></bbns-column>

  <bbns-column title="<?= _("Folder name") ?>"
               field="name"
  ></bbns-column>

  <bbns-column title="<?= _("Function Name") ?>"
               field="fname"
  ></bbns-column>

  <bbns-column title="<?= _("Latest") ?>"
               field="latest"
               cls="bbn-c"
               :width="80"
  ></bbns-column>
  <bbns-column title="<?= _('Infos') ?>"
               :render="showInfos"
               cls="bbn-c"
               :width="210"
  ></bbns-column>  
  <bbns-column cls="bbn-c buttonTable"
               :buttons="buttons"
               :width="130"
  ></bbns-column>
</bbn-table>
