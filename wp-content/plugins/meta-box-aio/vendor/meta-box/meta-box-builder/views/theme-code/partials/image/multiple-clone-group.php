<?php
$this->out( "\$clones = \$group[ '" . $field['id'] . "' ] ?? [];" );
$this->out( '?>' );
$this->out( '<h3>Uploaded images</h3>' );
$this->out( '<ul>' );
	$this->out( '<?php foreach ( $clones as $clone ) : ?>', 1 );
	$this->out( '<li>', 2 );
			$this->out( '<ul>', 3 );
				$this->out( '<?php foreach ( $clone as $image ) : ?>', 4 );
					$this->out( '<?php $image = RWMB_Image_Field::file_info( $image_id, [ \'size\' => \'thumbnail\' ); ?>', 5 );
					$this->out( '<li><img src="<?= $image[\'url\']; ?>"></li>', 5 );
				$this->out( '<?php endforeach ?>', 4 );
			$this->out( '</ul>', 3 );
	$this->out( '</li>', 2 );
	$this->out( '<?php endforeach ?>', 1 );
$this->out( '</ul>', 0, 1 );
$this->out( '<?php' );
