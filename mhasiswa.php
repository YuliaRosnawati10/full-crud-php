<?php $no = 1; ?>
                          <?php foreach ($data_mahasiswa as $mahasiswa) : ?>
                              <tr>
                                  <td><?= $no++; ?></td>
                                  <td><?= $mahasiswa['nama']; ?></td>
                                  <td><?= $mahasiswa['prodi']; ?></td>
                                  <td><?= $mahasiswa['jk']; ?></td>
                                  <td><?= $mahasiswa['telepon']; ?></td>
                                  <td class="text-center" style="white-space: nowrap;">
                                      <a href="detail-mahasiswa.php?id_mahasiswa=<?= $mahasiswa['id_mahasiswa']; ?>" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-eye"></i> Detail
                                      </a>

                                      <a href="ubah-mahasiswa.php?id_mahasiswa=<?= $mahasiswa['id_mahasiswa']; ?>" class="btn btn-success btn-sm">
                                        <i class="fas fa-edit"></i> Ubah
                                      </a>

                                      <a href="hapus-mahasiswa.php?id_mahasiswa=<?= $mahasiswa['id_mahasiswa']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin Data Mahasiswa Akan Dihapus?');">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                      </a>
                                  </td>
                              </tr>
                          <?php endforeach; ?>