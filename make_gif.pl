

#To change file extensions for images with .TIFF or variant, use this perl one liner in terminal:
#perl -we 'for my $old (glob "*.TIFF") { (my $new = $old) =~ s/\.TIFF\z/.tif/; rename $old, $new or warn "$old -> $new: $!\n"; }'
#perl -we 'for my $old (glob "*.tiff") { (my $new = $old) =~ s/\.tiff\z/.tif/; rename $old, $new or warn "$old -> $new: $!\n"; }'

while(<DATA>){
chomp;
$tif=$_;
print $tif, "\n";
system "mogrify -resize 500x500 -format gif $tif";
}
__END__
Ulva_tanneri.tif