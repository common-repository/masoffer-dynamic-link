<div class="wrap">
    <h1>MasOffer Dynamic Link</h1>

    <!-- using admin_action_ . $_REQUEST['action'] hook in admin.php -->
    <form action="<?= admin_url( 'admin.php' ); ?>" method="post">
    <input type="hidden" name="action" value="masoffer_dynamic_link_action" />
    <?php wp_nonce_field( 'update-pubid_' ); ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th><label for="publisher_id">Publisher ID</label></th>
                    <td>
                        <input name="publisher_id" id="publisher_id" type="text" value="<?= $publisher_id ?>" class="regular-text code">
                    </td>
                </tr>
                <tr>
                    <th><label for="publisher_token">Publisher token</label></th>
                    <td><input name="publisher_token" id="publisher_token" type="text" value="<?= $publisher_token ?>" class="regular-text code"></td>
                </tr>
                <tr>
                    <th><label for="exclude">Bỏ qua (link1,link2,...)</label></th>
                    <td><input name="exclude" id="exclude" type="text" value="<?= $exclude ?>" class="regular-text code"></td>
                </tr>
                <tr>
                    <th><label for="aff_sub1">Nguồn chiến dịch (aff_sub1)</label></th>
                    <td><input name="aff_sub1" id="aff_sub1" type="text" value="<?= $aff_sub1 ?>" class="regular-text code"></td>
                </tr>
                <tr>
                    <th><label for="aff_sub2">Cách tiếp thị (aff_sub2)</label></th>
                    <td><input name="aff_sub2" id="aff_sub2" type="text" value="<?= $aff_sub2 ?>" class="regular-text code"></td>
                </tr>
                <tr>
                    <th><label for="aff_sub3">Tên chiến dịch (aff_sub3)</label></th>
                    <td><input name="aff_sub3" id="aff_sub3" type="text" value="<?= $aff_sub3 ?>" class="regular-text code"></td>
                </tr>
                <tr>
                    <th><label for="aff_sub4">Nội dung chiến dịch (aff_sub4)</label></th>
                    <td><input name="aff_sub4" id="aff_sub4" type="text" value="<?= $aff_sub4 ?>" class="regular-text code"></td>
                </tr>
                <tr>
                    <th><label for="domain">Domain</label></th>
                    <td>
                        <select name="domain" id="domain" class="regular-text code">
                            <?php foreach ($parking_domains as $parking_domain) { ?>
                                <option value="<?= $parking_domain ?>"
                                    <?= $parking_domain == $domain ? 'selected': ''?>>
                                    <?= $parking_domain ?>
                                </option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="protocol">Protocol</label></th>
                    <td>
                        <select name="protocol" id="protocol" class="regular-text code">
                            <option value="https"
                                <?= 'https' == $protocol ? 'selected': ''?>>
                                https
                            </option>
                            <option value="http"
                                <?= 'http' == $protocol ? 'selected': ''?>>
                                http
                            </option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <input class="button button-primary" type="submit" name="update_settings" value="Save"/>
        </p>
        <div>
            <p><strong>Nhấn nút dưới đây để cập nhật parking domains trong danh sách domain</strong></p>
            <input class="button button-primary" type="submit" name="update_parking_domains" value="Cập nhật Parking domains"/>
        </div>
    </form>
</div> <!-- end div.wrap -->
