import 'package:flutter/material.dart';

class WorkModel with ChangeNotifier {
  String id;
  String title;
  String cover;
  String content;
  String images;
  String userId;
  String createdAt;

  WorkModel({
    @required this.id,
    @required this.title,
    @required this.images,
    @required this.cover,
    @required this.content,
    @required this.userId,
    @required this.createdAt,
  });
}
