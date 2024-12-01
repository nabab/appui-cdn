<bbn-splitter orientation="vertical">
  <bbn-pane>
    <div class="bbn-flex-height">
      <div class="bbn-w-100 bbn-header bbn-middle">
        <strong>
          "<?= _('Info') ?>"
        </strong>
      </div>
      <div class="bbn-padding bbn-flex-fill">
        <bbn-table
          title="<?= _("Information") ?>"
          :source="infos"
          :columns="colsInfo"
        >
        </bbn-table>
      </div>
    </div>
  </bbn-pane>
  <bbn-pane>
    <div class="bbn-flex-height">
      <div class="bbn-w-100 bbn-header bbn-middle">
        <strong>
          '<?= _('Versions') ?>"
        </strong>
      </div>
      <div class="bbn-flex-fill bbn-padding bbn-w-100">
        <bbn-table
          title="<?= _("Versions") ?>"
          :source="versions"
        >
          <bbns-column   title="<?= _("Name") ?>"
                        field="name"
                        :width="150"
                        cls="bbn-c"
          ></bbns-column>
          <bbns-column   title="<?= _("Date") ?>"
                        field="date"
                        :width="150"
                        type="date"
                        cls="bbn-c"
          ></bbns-column>
        </bbn-table>
      </div>
    </div>
  </bbn-pane>
</bbn-splitter>
