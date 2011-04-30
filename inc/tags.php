<?PHP

function showTags($quote) {

 echo '<p class="tags">';

 $sql = 'SELECT tag_text, COUNT(*) FROM tags WHERE quote_id = ' . $quote . ' GROUP BY tag_text';
 $res = mysql_query($sql);

 if (mysql_num_rows($res) == 0) {
  echo 'This quote has not been tagged yet';
 }

 while ($row = mysql_fetch_assoc($res)) {
  echo '  <a href="', BASE, 'tag?tag=', htmlentities($row['tag_text'], ENT_QUOTES, 'UTF-8'), '">';
  echo htmlentities($row['tag_text'], ENT_QUOTES, 'UTF-8'), '</a>';
 }

 echo '</p>';

}

function addAutoTags($id) {

 $sql = 'SELECT quote_quote FROM quotes WHERE quote_id = ' . $id;
 $res = mysql_query($sql);
 $text = mysql_result($res, 0);

 preg_match_all('/^(?:\[.*?\]|.*?\\|)?\s*<(?:[@+])?([^\s]*?)>/m', $text, $matches);

 foreach ($matches[1] as $user) {
  mysql_query('INSERT INTO tags (quote_id, tag_text, user_id) VALUES (' . $id . ', \'@' . m($user) . '\', 0)');
 }

}

?>
