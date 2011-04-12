<form action="" method="post">
    <?php wp_nonce_field( 'scrm_mc', 'scrm_mc_nonce' ); ?>
    <div class="postbox">
        <h3 class="hndle" ><?php _e( 'MailChimp Options','scrm_mc' )?></h3>
        <div class="inside">
            <div class="scrm-field-form">
                <p class="form-field">
                    <label for="scrm_mc_api_key">
                        <strong><?php _e( 'MailChimp API Key','scrm_mc' )?></strong>
                    </label>
                    <br />
                    <input id="scrm_mc_api_key" name="scrm_mc[api_key]" type="text" value="<?php echo $api_key; ?>"/>
                </p>
                <p class="form-field">
                    <label for="scrm_mc_list_id">
                        <strong><?php _e( 'MailChimp List ID','scrm_mc' )?></strong>
                    </label>
                    <br />
                    <?php if( $lists && $lists['total'] != 0 ): ?>
                        <select id="scrm_mc_list_id" name="scrm_mc[list_id]" >
                            <?php foreach( $lists['data'] as $l ): ?>
                                <option value="<?php echo $l['id']; ?>" <?php selected( $l['id'], $list_id ); ?> ><?php echo $l['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <?php _e( 'No api key or lists found. Start by adding one on MailChimp website.','scrm_mc' )?>
                    <?php endif; ?>
                </p>
            </div>
            <p>
                <small><em><?php _e( 'You can find these details by visiting your MailChimp <code>account &rarr; extras</code>.','scrm_mc' )?></em></small>
                <br />
                <input type="submit" class="button-primary" value="<?php _e( 'Save Changes' )?>"/>
            </p>
        </div>
    </div>
</form>

<form action="" method="post">
    <?php wp_nonce_field( 'scrm_mc_sync', 'scrm_mc_nonce' ); ?>
    <div class="postbox">
        <h3 class="hndle" ><?php _e( 'MailChimp Sync','scrm_mc' )?></h3>
        <div class="inside">
            <div class="scrm-field-form">
                <p class="form-field">
                    <?php printf( __( 'Currently you have %d user(s). You can start a synchronization process below.','scrm_mc' ), $counted_users ); ?>
                </p>
            </div>
            <p>
                <?php _e( 'If you experience timeouts or the synchronization process takes too long, try limiting the number of users.', 'scrm_mc' )?>
                <br />
                <label for="scrm_mc_start">
                    <strong><?php _e( 'Start with user', 'scrm_mc' )?>:</strong>
                </label>
                    <input type="text" name="scrm_mc_start" id="scrm_mc_start" value="0" style="width: 50px;" />
                <span style="font-size: 200%">&rarr;</span>
                <label for="scrm_mc_end">
                    <strong><?php _e( 'End with user', 'scrm_mc' )?>:</strong>
                </label>
                    <input type="text" name="scrm_mc_end" id="scrm_mc_end" value="<?php echo $counted_users; ?>" style="width: 50px;" />
            </p>
            <p>
                <input type="submit" class="button-primary" value="<?php _e( 'Sync Now', 'scrm_mc' )?>"/>
            </p>
        </div>
    </div>
</form>
