<bbn-splitter orientation="vertical">
  <bbn-pane>
    <div class="bbn-flex-height">
      <div class="bbn-w-100 k-header bbn-middle">
        <strong>
          "<?=_("INFO")?>"
        </strong>
      </div>
      <div class="bbn-padded bbn-flex-fill">
        <bbn-table
          title="<?=_("Information")?>"
          :source="infos"
          :columns="colsInfo"
        >
        </bbn-table>
      </div>
    </div>
  </bbn-pane>
  <bbn-pane>
    <div class="bbn-flex-height">
      <div class="bbn-w-100 k-header bbn-middle">
        <strong>
          '<?=_("VERSIONS")?>"
        </strong>
      </div>
      <div class="bbn-flex-fill bbn-padded bbn-w-100">
        <bbn-table
          title="<?=_("Versions")?>"
          :source="versions"
        >
          <bbn-column   title="<?=_("Name")?>"
                        field="name"
                        :width="150"
          ></bbn-column>
          <bbn-column   title="<?=_("Date")?>"
                        field="date"
                        :width="150"
                        type="date"
          ></bbn-column>
        </bbn-table>
      </div>
    </div>
  </bbn-pane>
</bbn-splitter>
