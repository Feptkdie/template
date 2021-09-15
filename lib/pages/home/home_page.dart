import '../../pages/favorite/favorite_page.dart';
import '../../pages/notifications/notifications_page.dart';
import '../../pages/profile/profile_page.dart';
import 'package:flutter/material.dart';

import '../../constants.dart';
import 'home_page_content.dart';

class HomePage extends StatefulWidget {
  const HomePage({Key key}) : super(key: key);

  @override
  _HomePageState createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  int _currentBottomIndex = 0;
  PageController _pageController;

  List<Widget> tabPages = [
    HomePageContent(),
    FavoritePage(),
    NotificationsPage(),
    ProfilePage(),
  ];

  @override
  void initState() {
    super.initState();
    _pageController = PageController(initialPage: _currentBottomIndex);
  }

  @override
  void dispose() {
    _pageController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    double height = MediaQuery.of(context).size.height;
    double width = MediaQuery.of(context).size.width;
    return Scaffold(
      bottomNavigationBar: _bottomNavigationBar(),
      body: Container(
        height: height,
        width: width,
        child: SafeArea(
          child: Column(
            children: <Widget>[
              Expanded(
                child: PageView(
                  children: tabPages,
                  onPageChanged: _onPageChanged,
                  controller: _pageController,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  void _onPageChanged(int page) {
    setState(() {
      _currentBottomIndex = page;
    });
  }

  Widget _bottomNavigationBar() => BottomNavigationBar(
        type: BottomNavigationBarType.fixed,
        fixedColor: kPrimaryColor,
        showSelectedLabels: false,
        showUnselectedLabels: false,
        currentIndex: _currentBottomIndex,
        onTap: (int index) {
          _pageController.animateToPage(index,
              duration: const Duration(milliseconds: 200),
              curve: Curves.easeInOut);
        },
        items: [
          BottomNavigationBarItem(
            label: 'Нүүр',
            icon: (_currentBottomIndex == 0)
                ? Icon(Icons.home)
                : Icon(Icons.home_outlined),
          ),
          BottomNavigationBarItem(
            label: 'Дуртай',
            icon: (_currentBottomIndex == 1)
                ? Icon(Icons.star)
                : Icon(Icons.star_border),
          ),
          BottomNavigationBarItem(
            label: 'Мэдэгдэл',
            icon: (_currentBottomIndex == 2)
                ? Icon(Icons.notifications)
                : Icon(Icons.notifications_outlined),
          ),
          BottomNavigationBarItem(
            label: 'Хэрэглэгчийн хуудас',
            icon: (_currentBottomIndex == 3)
                ? Icon(Icons.person)
                : Icon(Icons.person_outlined),
          ),
        ],
      );
}
